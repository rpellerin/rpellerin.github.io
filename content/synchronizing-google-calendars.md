Title: Synchronizing Google Calendars
Date: 2025-11-18 16:30
Category: Code
Tags: code, google
Slug: synchronizing-google-calendars
Authors: Romain Pellerin
Summary: A Google Apps Script to keep calendars in sync

At work, we use Google Calendars. I'm scheduling my workout/sports sessions in a separate, private Google Calendar. I'm also regularly on call during office hours - I'm getting assigned some days through another calendar provided by PagerDuty.

<img src="{static}/images/google-calendar-others.png" alt="Screenshot of Google Calendar" style="float:left" />

I added these 2 additional calendars to my work calendar as "external calendars", which only I can see.

But I need my colleagues to see when I'm busy because I'm going for a run over the lunch break, or when I'm on call. Hence a need to keep these 3 calendars in sync. One caveat though is that I want to keep my sports sessions "private": my coworkers do not need to know whether I'm going running, or to the gym, or whathever. They just need to know that I'm not available.

So I came up with a [Google Apps Script](https://script.google.com/home/) that periodically runs (every hour) and does the following:

- Create "busy" events in my work calendar at the same time as my workouts.
- Create a copy of my "on call" events in my work calendar, coming from the Pager Duty calendar.

Of course if I update or delete a sports session, or if my on-call schedule changes, it gets updated in my work calendar too.

Here is the script:

    :::javascript
    const CONFIG = {
        TARGET_EMAIL: "romain.pellerin@workemail",
        SYNC_DAYS: 6, // Number of days to look ahead
        TAG_PREFIX: "Source iCalUID:", // Unique identifier tag in description
        TIME_WINDOW: {
            START_HOUR: 8,
            START_MINUTE: 30,
            END_HOUR: 19,
            END_MINUTE: 0,
        },
        CALENDARS: [
            {
                id: "some-id@group.calendar.google.com", // sports
                isPrivate: true, // Will be renamed to 'Busy' and set private
            },
            {
                id: "some-other-id@import.calendar.google.com", // pager duty
                isPrivate: false,
            },
        ],
    };

    // Convert limit times to minutes from start of day
    const limitStartMins = CONFIG.TIME_WINDOW.START_HOUR * 60 + CONFIG.TIME_WINDOW.START_MINUTE;
    const limitEndMins = CONFIG.TIME_WINDOW.END_HOUR * 60 + CONFIG.TIME_WINDOW.END_MINUTE;

    // THE MAIN FUNCTION, to call periodically.
    function syncGoogleCalendars() {
        const userEmail = Session.getActiveUser().getEmail();
        if (userEmail !== CONFIG.TARGET_EMAIL) {
            Logger.log(`Skipping: Current user (${userEmail}) is not the target user.`);
            return;
        }

        const now = new Date();
        const futureDate = new Date(
            now.getTime() + CONFIG.SYNC_DAYS * 24 * 60 * 60 * 1000
        );

        const targetCalendar = CalendarApp.getDefaultCalendar();

        // Creates a Map: { 'source_uid_string' : CalendarEventObject }
        const existingEventsMap = getExistingManagedEvents(
            targetCalendar,
            now,
            futureDate
        );

        CONFIG.CALENDARS.forEach((sourceCalendarConfig) => {
            syncSourceCalendar(
            sourceCalendarConfig,
            targetCalendar,
            existingEventsMap,
            now,
            futureDate
            );
        });

        // Cleanup orphans: any event remaining in the map was NOT found in the source calendars, so it should be deleted.
        cleanupOrphanedEvents(existingEventsMap);
    }

    /**
    * Fetches events from the target calendar and maps them by their Source UID.
    * Only includes events created by this script (checked via regex).
    */
    function getExistingManagedEvents(calendar, start, end) {
        const events = calendar.getEvents(start, end);
        const eventMap = new Map();
        const uidRegex = new RegExp(`${CONFIG.TAG_PREFIX}\\s*([\\w.@-]+)`);

        events.forEach((event) => {
            const match = event.getDescription().match(uidRegex);

            if (match && match[1]) {
            eventMap.set(`${event.getStartTime()}-${match[1]}`, event); // Map Key: the source event UID
            }
        });

        return eventMap;
    }

    function syncSourceCalendar(sourceCalendarConfig, targetCalendar, existingEventsMap, start, end) {
        const sourceCalendar = CalendarApp.getCalendarById(sourceCalendarConfig.id);
        if (!sourceCalendar) {
            Logger.log(`Error: Could not find calendar ${sourceCalendarConfig.id}`);
            return;
        }

        const sourceEvents = sourceCalendar.getEvents(start, end);
        Logger.log(
            `Processing ${sourceCalendarConfig.id}: ${sourceEvents.length} events found.`
        );

        sourceEvents.forEach((sourceEvent) => {
            // Weekday Filter
            const startTime = sourceEvent.getStartTime();
            const endTime = sourceEvent.getEndTime();
            const dayOfWeek = startTime.getDay(); // 0 = Sunday, 1 = Monday, ... 6 = Saturday

            // If it is Sunday (0) or Saturday (6), skip this event.
            if (dayOfWeek === 0 || dayOfWeek === 6) {
            Logger.log(
                `Skipping event on weekend from ${
                sourceCalendarConfig.id
                }: ${sourceEvent.getTitle()} on ${startTime}`
            );
            return;
            }

            // Convert event times to minutes
            const eventStartMins = startTime.getHours() * 60 + startTime.getMinutes();
            const eventEndMins = endTime.getHours() * 60 + endTime.getMinutes();

            if (eventStartMins >= limitEndMins || eventEndMins <= limitStartMins) {
                Logger.log(
                    `Skipping event outside time window (${CONFIG.TIME_WINDOW.START_HOUR}:${CONFIG.TIME_WINDOW.START_MINUTE} - ${CONFIG.TIME_WINDOW.END_HOUR}:${CONFIG.TIME_WINDOW.END_MINUTE}): "${sourceEvent.getTitle()}" (${startTime.getHours()}:${startTime.getMinutes()} - ${endTime.getHours()}:${endTime.getMinutes()})`
                );
                return;
            }

            const sourceUid = sourceEvent.getId();
            const eventKey = `${sourceEvent.getStartTime()}-${sourceUid}`;
            const existingEvent = existingEventsMap.get(eventKey);

            // Determine Title and Visibility based on privacy setting
            const targetTitle = sourceCalendarConfig.isPrivate
            ? "Busy"
            : sourceEvent.getTitle();

            if (existingEvent) {
            updateEventIfChanged(existingEvent, sourceEvent, targetTitle);
            existingEventsMap.delete(eventKey); // Remove from map to indicate this event is still valid (not an orphan)
            } else {
            createNewEvent(
                targetCalendar,
                sourceEvent,
                sourceUid,
                targetTitle,
                sourceCalendarConfig.isPrivate
            );
            }
        });
    }

    function createNewEvent(targetCalendar, sourceEvent, sourceUid, title, isPrivate) {
        Logger.log(`Creating "${title}" on ${sourceEvent.getStartTime()}`);

        const options = {
            description: `${CONFIG.TAG_PREFIX} ${sourceUid}\nCreated by Google Apps Script`,
            location: sourceEvent.getLocation(),
            sendInvites: false,
        };

        let newEvent;
        if (sourceEvent.isAllDayEvent()) {
            newEvent = targetCalendar.createAllDayEvent(
            title,
            sourceEvent.getStartTime(),
            options
            );
        } else {
            newEvent = targetCalendar.createEvent(
            title,
            sourceEvent.getStartTime(),
            sourceEvent.getEndTime(),
            options
            );
        }

        // Set specific privacy settings
        if (isPrivate) {
            newEvent.setVisibility(CalendarApp.Visibility.PRIVATE);
        }

        newEvent.removeAllReminders();
    }

    /**
    * Note: We blindly update title/time to ensure data consistency.
    * To further optimize, you could check if values differ before setting.
    */
    function updateEventIfChanged(targetEvent, sourceEvent, targetTitle) {
        // We update the title to ensure "Busy" is maintained if the config changed or privacy was toggled
        targetEvent.setTitle(targetTitle);

        if (sourceEvent.isAllDayEvent()) {
            // For all day events, we generally just check the day, but setAllDayDate is safe
            targetEvent.setAllDayDate(sourceEvent.getStartTime());
        } else {
            targetEvent.setTime(sourceEvent.getStartTime(), sourceEvent.getEndTime());
        }
    }

    /**
    * Deletes any events remaining in the map.
    */
    function cleanupOrphanedEvents(orphanMap) {
        if (orphanMap.size === 0) {
            Logger.log("No orphaned events to delete.");
            return;
        }

        Logger.log(`Deleting ${orphanMap.size} orphaned events...`);

        for (const [starttime_uid, event] of orphanMap) {
            try {
                Logger.log(
                    `Deleting ${event.getTitle()} (Source UID: ${event.getId()}) on ${event.getStartTime()}`
                );
                event.deleteEvent();
            } catch (e) {
                Logger.log(`Failed to delete event ${event.getId()} on ${event.getStartTime()}: ${e.message}`);
            }
        }
    }

<figure class="center">
<img src="{static}/images/google-calendar-result.png" alt="A screenshot of Google Calendar" />
<figcaption>And here is the result. In gray and purple are the original events from the external calendars.</figcaption>
</figure>
