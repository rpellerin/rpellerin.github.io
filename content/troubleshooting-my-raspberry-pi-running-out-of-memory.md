Title: Troubleshooting My Raspberry Pi Running Out of Memory
Date: 2026-04-28 23:00
Category: Linux
Tags: python, linux, raspberry pi
Slug: troubleshooting-my-raspberry-pi-running-out-of-memory
Authors: Romain Pellerin
Summary: How a missing timeout in a Python script triggered a chain reaction that took down my Raspberry Pi

I recently noticed a strange behavior on my Raspberry Pi: many of my systemctl services were being killed and rebooted infinitely. Looking at the logs via `journalctl`, I saw the dreaded:

```text
systemd[1]: systemd-journald.service: Main process exited, code=killed, status=9/KILL
systemd[1]: some-other-service.service: Main process exited, code=killed, status=9/KILL
```

A `status=9` (SIGKILL) means the process didn't just crash; it was executed by the system. On a Raspberry Pi, this is almost always the **OOM (Out of Memory) Killer** at work. This was confirmed by checking `journalctl -b -1 -e` after rebooting.

`journalctl -b -1 | grep -i "out of memory"` gave many:

    :::text
    kernel: Out of memory: Killed process 28829 (python3) total-vm:421072kB, anon-rss:6740kB, file-rss:0kB, shmem-rss:4kB, UID:1000 pgtables:284kB oom_score_adj:0
    kernel: Out of memory: Killed process 28189 (python3) total-vm:105076kB, anon-rss:6392kB, file-rss:0kB, shmem-rss:0kB, UID:1000 pgtables:70kB oom_score_adj:0
    kernel: Out of memory: Killed process 18904 (python3) total-vm:32424kB, anon-rss:0kB, file-rss:0kB, shmem-rss:0kB, UID:1000 pgtables:42kB oom_score_adj:0
    kernel: Out of memory: Killed process 10876 (python3) total-vm:53648kB, anon-rss:13840kB, file-rss:0kB, shmem-rss:0kB, UID:1000 pgtables:60kB oom_score_adj:0

But why was my Raspberry Pi running out of memory?

# The Investigation

Using `pstree -apl | grep -A 3 cron`, I found the culprit. Many of my cron jobs (launching Python scripts) were piling up. Instead of running once and exiting, dozens of instances were sitting in the background, stagnant.

I attached to one of the hanging processes using `strace`:

```bash
sudo strace -p <PID> # Finding the child Python process of the hanging cronjob
# Output: read(4, ...
```

Then, I used `lsof` to see what file descriptor `4` was:

```bash
sudo lsof -p <PID>
# Output: ... IPv6 TCP ... ->tzhama-ab-in-x0e.1e100.net:https (ESTABLISHED)
```

The script was stuck in a "Read" state on a TCP connection to a Google domain (a legit operation from many of my cron jobs). It had established a connection, but the server was ghosting it, and the script was waiting... **forever.**

# The Chain Reaction

This created a perfect storm:

1.  **The hang:** a network glitch caused `requests.get()` or `requests.post()` to wait indefinitely.
2.  **The accumulation:** cron started a new instance every 2 minutes.
3.  **The OOM:** aach hanging process consumed many MB of RAM until the Pi hit its limit.
4.  **The victim:** the Linux kernel sacrificed my services to keep the system alive, but they were set to auto restart. Infinitely.

        :::text
        [Service]
        Restart=always
        RestartSec=3

# The Solution

The fix was two-fold. First, I added a safety net to the crontab using the `timeout` command. I could have added on top `flock` to prevent overlapping, but I did not:

```bash
*/2 * * * * /usr/bin/timeout 50s /path/to/python_script
```

Second, and most importantly, I fixed the Python code. **Never make a network request without a timeout.**

```python
import requests

try:
    # Adding a 10-second timeout
    response = requests.post('https://api.example.com', json=data, timeout=10)
    response.raise_for_status()
except requests.exceptions.Timeout:
    print("Server took too long!")
```

# Conclusion

If your Raspberry Pi services are dying with `status=9`, don't just restart them. Check your process tree. A single script without a network timeout can eventually bring down your entire automation stack. I hope this helps!
