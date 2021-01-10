Title: Building A Cheap Weather Station
Date: 2020-12-13 23:50
Category: Linux
Tags: raspberry pi, weather
Slug: building-a-cheap-weather-station
Authors: Romain Pellerin
Summary: How to build a cheap weather station with a Raspberry Pi and the BME280 module

Hi there! Long time no talk, uh?

In this article, I explain how I managed to build a very cheap weather station to monitor the temperature and humidity of my apartment as well as the pressure through automated reporting to Google Spreadsheets. Why Google Spreadsheets? Because it allows me to create nice graphs that I can publish, or in other words, access through a public link.

Then I can build a simple HTML web page to display those graphs from Google Spreadsheet, like this:

<figure class="center">
<img src="{filename}/images/weather-station.png" alt="My weather station webpage" />
<figcaption>My weather station webpage</figcaption>
</figure>

For this tutorial you'll need:

- A Raspberry Pi
- A BME280 module and 4 cables
- A Google Form that you link to a spreadsheet. It's so much easier than using the Google Spreadsheet API. Your Raspberry PI will POST a form with the values and they'll automatically end up in the sheet.

<figure class="center">
<img src="{filename}/images/bme280.jpg" alt="The BME280 module plugged to the Raspberry Pi" />
<figcaption>The BME280 module plugged to the Raspberry Pi.</figcaption>
</figure>

# Step by step tutorial

*This tutorial was greatly inspired by [that tutorial](https://github.com/rm-hull/bme280).*

1. Raspberry Pi turned off, plug the module like shown in the photo above.
1. Enable I2C. Run `sudo raspi-config`, choose `Interfacing options`. Then reboot. Run `lsmod | grep i2c` to confirm `i2c` has been enabled.
1. Run `sudo apt install python3-pip i2c-tools && i2cdetect -y 1` to make sure the BME280 module is detected.
1. `mkdir /home/pi/temperature && cd /home/pi/temperature` (or any other directory of your liking).
1. `pip3 install virtualenv` (no `sudo`!)
1. `/home/pi/.local/bin/virtualenv -p /usr/bin/python3 .env && source .env/bin/activate`
1. `pip install smbus2 requests RPi.bme280 redis`
1. Create the Google Form, add 4 free text inputs: datetime, temperature, humidity and pressure. Then navigate to the form and inspect the DOM, you should be able to find hidden inputs whose names contain the word "entity" and a &lt;form&gt; whose URL ends with `/formResponse`. Copy the URL and the hidden input names, you'll need them in the next bullet point.
1. `vim weatherstation.py`

        :::python
        import smbus2
        import bme280
        import time
        import requests
        import datetime
        import redis

        url="https://docs.google.com/forms/TOKEN/formResponse"

        port = 1
        address = 0x76
        bus = smbus2.SMBus(port)
        utc_offset_in_hours = int(-time.timezone/3600)
        now = datetime.datetime.now(datetime.timezone(datetime.timedelta(hours=utc_offset_in_hours))).strftime('%d/%m/%Y %H:%M:%S')

        calibration_params = bme280.load_calibration_params(bus, address)
        def send_request(data):
            try:
                response = requests.post(
                    url,
                    params={
                        "entry.12": data['timestamp'], 
                        "entry.34": data['temperature'],
                        "entry.56": data['humidity'],
                        "entry.78": data['pressure'],
                    },
                    headers={
                        "Content-Type": "application/octet-stream",
                    },
                )
                return response.status_code == 200
            except requests.exceptions.RequestException:
                return False


        raw_data = bme280.sample(bus, address, calibration_params)
        data = { 'timestamp': now, 'temperature': raw_data.temperature, 'humidity': raw_data.humidity, 'pressure': raw_data.pressure }

        successfully_sent = False

        try:
            successfully_sent = send_request(data)
        except BaseException as e:
            print("Error: %s" % str(e))

        if not successfully_sent:
            print('Failed to post to Google Form')
            print(data)
            r = redis.Redis()
            r.rpush('weather_reports', str(data))


1. Finally, let's make it post values every 5 minutes through `crontab -e`: `*/5 * * * * /home/pi/temperature/.env/bin/python /home/pi/temperature/weatherstation.py`

That's it!!!
