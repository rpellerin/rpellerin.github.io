Title: Running Calculators
Date: 2024-08-24 13:00
Category: Running
Tags: running, training, marathon
Slug: running-calculators
Authors: Romain Pellerin
Summary: A bunch of calculators for runners

<script>
    const matchPaceFormatWithColon = v => v && v.match(/^(\d{1,2}):(\d{2})$/);
    const matchPaceFormatWithQuotes = v => v && v.match(/^(\d{1,2})'(\d{2})"$/);
    const paceToSeconds = (v, separator = 'COLON') => {
            const [, minutes, seconds] = separator === 'COLON' ? matchPaceFormatWithColon(v) : matchPaceFormatWithQuotes(v)
            return Number(minutes) * 60 + Number(seconds)
    }
    const differenceBetweenPaces = (min, max) => paceToSeconds(max) - paceToSeconds(min)
    const secondsToTime = (v) => {
            let minutes = Math.floor(v / 60)
            let hours = 0
            const seconds = String(v % 60).padStart(2, '0')
            if (minutes > 59) {
                    hours = Math.floor(minutes / 60)
                    minutes = String(minutes % 60).padStart(2, '0')
            }
            minutes = String(minutes)
            const result = [String(hours), minutes, seconds]
            result.valueWithQuotes = hours ? `${hours}h${minutes}'${seconds}"` : `${minutes}'${seconds}"`
            result.valueWithColon = hours ? `${hours}:${minutes}:${seconds}` : `${minutes}:${seconds}`
            return result
    }
    const roundTo1 = num => Math.round((num + Number.EPSILON) * 10) / 10
    const paceInSecondsToSpeed = paceInSeconds => roundTo1(3600.0/paceInSeconds) // .toFixed(1) is less precise
    const speedInKmhTimesPercent = (kmh, percent) => (percent * kmh / 100).toFixed(2)
    const paceInSecondsToFinishTime = (paceInSeconds, kms) => secondsToTime(Math.ceil(paceInSeconds * kms)).valueWithColon
    const secondsAtPaceToMeters = (seconds, paceInSeconds) => Math.floor((seconds * 1000) / paceInSeconds)
    const metersAtPaceToSeconds = (meters, paceInSeconds) => Math.ceil((meters * paceInSeconds) / 1000)
    const speedToSecondsForOneKilometer = speed => Math.ceil(3600/speed)
</script>

# MAS, HR and zones

(MAS is _VMA_ in French)

There are multiple scales that exist out there, but none of them will have exactly the same values nor the same zones. Here are two scales that I personnally use. They work for me, they might not for you.

The first table is greatly inspired by [this one](https://www.facebook.com/lorblanchet/posts/pfbid032J13PKC2rDPA84weL5dXZ9G8GpznZBVwgrqZszF6opB121oEpwqKZ7hjNQ2NCehel), even though I found the given HRmax values way too high, compared to the MAS values. Generally, based on what I read on the internet but also my observations, for any pace, `(percent of MAS plus 5 to 10) = percent of your HRmax` (more or less). So I changed the HRmax values in the first table below and adjusted them to what I observed with my very own heart, at those paces. Most other scales on the internet seem to agree and use the formula `percent of MAS + 5 = percent of HRmax`.

In recent years, many pace charts started using the "heart rate reserve" as their basis, instead of HRmax. I have not dug the topic enough, so I'm not using that (so far). "Heart rate reserve" basically means HRmax - resting heart rate. Not everyone agrees on how the "resting heart rate" should be measured. Lowest value in the night? Lowest value in the day, while sitting and doing nothing? Average value measured during the sleep? Therefore, I'm quite reluctant to use this for now.

The second table, on ventilatory thresholds, is a mashup of multiple articles I've read. I simplified the values to make it easier to understand.

<input type="number" step="0.01" id="mas" placeholder="MAS speed (km/h)" value="16.95"/>
<input type="number" step="1" id="maxhr" placeholder="Max HR" value="202"/>

<div>
<input type="number" step="1" id="random_percent" placeholder="% of your MAS" value="65"/>
<span id="random_percent_result"></span>
</div>

<div id="zones_result"></div>

In [other sources](https://youtu.be/ZDdZ3TqJkd8?t=471), I've found slightly different values for SV1 (_VT1_ in English) and SV2 (_VT2_ in English). But these values are athlete-dependant anyways. It's never an exact value. And it's different for everyone.

<script>
  const masInput = document.querySelector('input#mas')
  const maxHrInput = document.querySelector('input#maxhr')
  const randomPercent = document.querySelector('input#random_percent')

  const zones = [
    [
      {percentHr: [70], percentMas: [50,60], zone: 1, name: 'Endurance fondamentale'},
      {percentHr: [70,75], percentMas: [60,70], zone: 2, name: 'Endurance active'},
      {percentHr: [75,85], percentMas: [70,80], zone: 3, name: 'Allure marathon'},
      {percentHr: [85,95], percentMas: [80,90], zone: 4, name: 'Allures semi→10km, allure tempo, allure "au seuil" [anaérobie]'},
      {percentHr: [95,100], percentMas: [90,100], zone: 5, name: 'Allures 5km→VMA'},
    ],
    [
      {percentHr: [80], percentMas: [75], zone: 1, name: 'Sous le seuil aérobie / seuil ventilatoire 1 (SV1) = endurance fondamentale'},
      {percentHr: [80,90], percentMas: [75,85], zone: 2, name: 'Entre le seuil aérobie (SV1) et anaérobie (SV2) = allures marathon→semi'},
      {percentHr: [90,100], percentMas: [85,100], zone: 3, name: 'Au delà de SV2 = allures 10km→VMA'},
    ]
  ]

  function MASzonesInputChange() {
    if (!masInput.value) return

    document.querySelector('#zones_result').innerHTML = ""
    let randomPercentResult
    if (randomPercent.value) {
      const speed = speedInKmhTimesPercent(masInput.value, randomPercent.value)
      randomPercentResult = `= ${secondsToTime(speedToSecondsForOneKilometer(speed)).valueWithQuotes} (${speed} km/h)`
    }
    else {
      randomPercentResult = ""
    }
    document.querySelector('span#random_percent_result').innerHTML = randomPercentResult

    zones.forEach((zones,index,array) => {
        let newTable = "<table class=\"collapse\"><thead><tr><th>Zone</th><th>Name(s)</th><th>% MAS</th><th>% HRmax</th><th>Pace</th></tr></thead><tbody>"

        const hueStep = (120 / (zones.length - 1))
        zones.map(({percentMas,zone,percentHr,name},index,array) => {
          const hue = 120 - (index * hueStep)
          const color = `hsl(${hue}, 100%, 50%)`
          const speeds = percentMas.map(percent => speedInKmhTimesPercent(masInput.value, percent))
          const paces = speeds.map(speed => secondsToTime(speedToSecondsForOneKilometer(speed)).valueWithQuotes)
          const hrs = maxHrInput.value ? percentHr.map(hr => Math.round(maxHrInput.value * hr / 100)) : []
          newTable += `<tr style="background-color: ${color}"><td>${zone}</td><td>${name}</td>`
          newTable += `<td>${percentMas.length === 1 ? '<=&nbsp;' : ''}${percentMas.join('&nbsp;-&nbsp;')}%<br />${speeds.join('&nbsp;-&nbsp;')}&nbsp;km/h</td>`
          newTable += `<td>${percentHr.length === 1 ? '<=&nbsp;' : ''}${percentHr.length > 0 ? `${percentHr.join('&nbsp;-&nbsp;')}%` : ''}${hrs.length > 0 ? `<br />${hrs.join('&nbsp;-&nbsp;')}&nbsp;bpm` : ''}</td>`
          newTable += `<td>${paces.join('&nbsp;-&nbsp;')}</td>`
          newTable += `</tr>`
        })

        newTable += "</tbody></table>"
        document.querySelector('#zones_result').innerHTML += newTable
    })
  }

  masInput.addEventListener('input', MASzonesInputChange)
  maxHrInput.addEventListener('input', MASzonesInputChange)
  randomPercent.addEventListener('input', MASzonesInputChange)
  MASzonesInputChange()
</script>

## Session parser

To categorize the paces into zone 1 (below VT1), zone 2 (VT1 to VT2) or zone 3 (above VT2), the MAS value your entered at the top of this page will be used.

<div style="display: flex">
<textarea style="flex: 1 0 0" id="session_parser" rows="10" cols="0">
30min warm-up 5'53" working towards 5'03"
3000m 3'55"
10min cool-down 5'53"
45min 5'26" working towards 5'03"
20min warm-up 5'53" working towards 5'26"
8x (400m / 1min24 3'32" + 200m recovery 5'53")
10min cool-down 5'53"
6km 5'53" working towards 5'26"
4km 4'57"
</textarea>

<textarea style="flex: 1 0 0; background-color: #ddd" id="session_parser_results" rows="10" cols="0" readonly></textarea>

</div>

<div id="session_parser_zones_results"></div>

<script>
    const sessionParserTextarea = document.querySelector('textarea#session_parser')
    const sessionParserResults = document.querySelector('#session_parser_results')

    const regexWithMultiplicator = /^(?<multiplicator>\d+)\s?x\s?\((?<rest>.*?)\)$/
    const regexWorkingTowards = /(?<paceA>\d{1,2}'\d{2}")\s?working towards\s?(?<paceB>\d{1,2}'\d{2}")/g

    // ([a-z-]+ )? is for recovery/warm-up/cool-down terms
    const regexFindMeters = /(?<meters>\d{2,5})\s?m (?:\/ \dmin\d{1,2} )?([a-z-]+ )?(?<pace>\d{1,2}'\d{2}")/g
    const regexFindKiloMeters = /(?<kilometers>\d{1,2}(\.\d{1,3})?)\s?km ([a-z-]+ )?(?<pace>\d{1,2}'\d{2}")/g
    const regexMinsSecsToDistance = /(?<mins>\d{1,3})\s?mins?(?<secs>\d{1,2})? ([a-z-]+ )?(?<pace>\d{1,2}'\d{2}")/g

    function sessionParserInputChange() {
        const zone1 = [] // % MAS <= 75
        const zone2 = [] // % MAS <= 85
        const zone3 = [] // % MAS > 85

        const vt1Speed = speedInKmhTimesPercent(masInput.value, 75)
        const vt2Speed = speedInKmhTimesPercent(masInput.value, 85)
        const vt1PaceInSeconds = speedToSecondsForOneKilometer(vt1Speed)
        const vt2PaceInSeconds = speedToSecondsForOneKilometer(vt2Speed)

        const lines = sessionParserTextarea.value.split("\n")
        const results = lines.map(l => l.trim()).filter(l => l.length > 0).map(line => {
                let lineToParse = line
                let multiplicator = 1

                let result = lineToParse.match(regexWithMultiplicator)
                if (result) {
                        multiplicator *= result.groups.multiplicator
                        lineToParse = result.groups.rest
                }

                // Let's replace the "working towards" with their average pace
                result = lineToParse.matchAll(regexWorkingTowards)
                for (const match of result) {
                        const {paceA, paceB} = match.groups
                        const toReplace = match[0]
                        let averagePace = Math.ceil((paceToSeconds(paceA, 'QUOTE') + paceToSeconds(paceB, 'QUOTE')) / 2)
                        averagePace = secondsToTime(averagePace).valueWithQuotes
                        lineToParse = lineToParse.replace(toReplace, averagePace)
                }

                // Let's replace `400m 4'57"` with `4000@4'57"`
                result = lineToParse.matchAll(regexFindMeters)
                for (const match of result) {
                        const {meters, pace} = match.groups
                        const toReplace = match[0]
                        const metersAtPace = `${meters}@${pace}`
                        lineToParse = lineToParse.replace(toReplace, metersAtPace)
                }
                // Let's replace `4km 4'57"` with `4000@4'57"`
                result = lineToParse.matchAll(regexFindKiloMeters)
                for (const match of result) {
                        const {kilometers, pace} = match.groups
                        const toReplace = match[0]
                        const metersAtPace = `${Number(kilometers)*1000}@${pace}`
                        lineToParse = lineToParse.replace(toReplace, metersAtPace)
                }
                // Let's replace `10min cool-down 5'53" with 1700@5'53"` with `1700@5'53"`
                result = lineToParse.matchAll(regexMinsSecsToDistance)
                for (const match of result) {
                        const {mins, secs, pace} = match.groups
                        const toReplace = match[0]
                        const paceInSeconds = paceToSeconds(pace, "QUOTES")
                        const meters = secondsAtPaceToMeters((Number(mins) * 60) + Number(secs ?? "0"), paceInSeconds)
                        const metersAtPace = `${meters}@${pace}`
                        lineToParse = lineToParse.replace(toReplace, metersAtPace)
                }

                let segments
                if (lineToParse.includes('+')) {
                        segments = lineToParse.split(/\s?\+\s?/)
                }
                else {
                        segments = [lineToParse]
                }
                if (!segments.every(segment => segment.match(/^\d+@\d{1,2}'\d{2}"$/))) {
                        return "Line could not be parsed"
                }

                segments = segments.map(segment => {
                        let [meters, pace] = segment.split('@')
                        meters *= multiplicator
                        const paceInSeconds = paceToSeconds(pace, "QUOTES")
                        if (paceInSeconds >= vt1PaceInSeconds) zone1.push(meters)
                        else if (paceInSeconds >= vt2PaceInSeconds) zone2.push(meters)
                        else zone3.push(meters)

                        return `${meters}@${pace}`
                })
                return segments.join(" + ")
        })

        sessionParserResults.innerHTML = results.filter(l => l.length > 0).join("\n")

        const metersInZone1 = zone1.reduce((acc, item) => acc + item, 0)
        const metersInZone2 = zone2.reduce((acc, item) => acc + item, 0)
        const metersInZone3 = zone3.reduce((acc, item) => acc + item, 0)
        const zonesResults = document.querySelector('#session_parser_zones_results')
        zonesResults.innerHTML = `Zone 1: ${metersInZone1/1000}kms<br />Zone 2 (faster than ${secondsToTime(vt1PaceInSeconds).valueWithQuotes}): ${metersInZone2/1000}kms<br />Zone 3 (faster than ${secondsToTime(vt2PaceInSeconds).valueWithQuotes}): ${metersInZone3/1000}kms<br /><button id="sessionCopy">Copy</button>`

        const copyContent = async () => {
                try {
                        // Fo google spreadsheet, columns "quality kms in zone 2" and "quality kms in zone 3"
                        await navigator.clipboard.writeText(`${metersInZone2/1000}\t${metersInZone3/1000}`);
                        console.log('Content copied to clipboard');
                } catch (err) {
                        console.error('Failed to copy: ', err);
                }
        }
        document.querySelector('button#sessionCopy').onclick = copyContent
    }
    sessionParserTextarea.addEventListener('input', sessionParserInputChange)
    masInput.addEventListener('input', sessionParserInputChange)
    sessionParserInputChange()
</script>

# Time and distance converter

<div id="time_and_distance_converter">
<div>Pace: <input pattern="\d{1,2}:\d{2}" type="text" id="time_and_distance_converter_pace" placeholder="Pace" value="4:25"/></div>
<div>What distance will I cover if I run for <input type="number" step="1" id="time_to_distance_mins" value="30"/> minutes and <input type="number" step="1" id="time_to_distance_secs" value="0"/> seconds? <strong id="time_to_distance_results"></strong></div>
<div>How much time do I need to run <input type="number" step="1" id="distance_to_time" value="30"/> meters? <strong id="distance_to_time_results"></strong></div>
</div>

<script>
    function timeAndDistanceConverterInputChange() {
        const pace = document.querySelector('input#time_and_distance_converter_pace').value
        if (!matchPaceFormatWithColon(pace)) return

        const paceInSeconds = paceToSeconds(pace)

        let timeInSecondsToConvert = Number(document.querySelector('input#time_to_distance_secs').value ?? '0')
        const timeInMinutesToConvert = Number(document.querySelector('input#time_to_distance_mins').value ?? '0')
        timeInSecondsToConvert += (timeInMinutesToConvert * 60)
        const timeConvertedInDistance = secondsAtPaceToMeters(timeInSecondsToConvert, paceInSeconds)
        document.querySelector('#time_to_distance_results').innerText = `${timeConvertedInDistance}m`

        const metersToConvert = Number(document.querySelector('input#distance_to_time').value ?? '0')
        const [hours, minutes, seconds] = secondsToTime(metersAtPaceToSeconds(metersToConvert, paceInSeconds))
        document.querySelector('#distance_to_time_results').innerText = hours ? `${hours}hrs ${minutes}mins ${seconds}secs` : `${minutes}mins ${seconds}secs`
    }
    document.querySelectorAll('#time_and_distance_converter input').forEach(el => {
        el.addEventListener('input', timeAndDistanceConverterInputChange)
    })
    timeAndDistanceConverterInputChange()
</script>

# Pace chart

<div id="pace_chart_info">
<input pattern="\d{1,2}:\d{2}" type="text" id="fastest_pace" placeholder="Fastest pace" value="4:25"/>
<input pattern="\d{1,2}:\d{2}" type="text" id="slowest_pace" placeholder="Slowest pace" value="5:42"/>

<div class="distance"><input data-kms="42.195" id="marathon" type="checkbox" checked /><label for="marathon">Marathon</label></div>
<div class="distance"><input data-kms="25" id="km25" type="checkbox" /><label for="km25">25 kms</label></div>
<div class="distance"><input data-kms="21.0975" id="half_marathon" type="checkbox" /><label for="half_marathon">Half-marathon</label></div>
<div class="distance"><input data-kms="10" id="km10" type="checkbox" /><label for="km10">10 kms</label></div>
<div class="distance"><input data-kms="5" id="km5" type="checkbox" /><label for="km5">5 kms</label></div>
<div class="distance"><input data-kms="custom" type="checkbox" /><input type="number" step="0.1" id="custom" placeholder="Custom distance" value="3.5"/></div>
</div>

<table class="collapse" id="pace_chart_results"></table>

<script>
    const fastestPace = document.querySelector('input#fastest_pace')
    const slowestPace = document.querySelector('input#slowest_pace')
    const custom = document.querySelector('input#custom')

    function paceChartInputChange() {
        custom.parentElement.querySelector('[data-kms]').dataset.kms = custom.value

        const min = fastestPace.value;
        const max = slowestPace.value;

        if (!matchPaceFormatWithColon(min) || !matchPaceFormatWithColon(max) || differenceBetweenPaces(min, max) < 0) return
        const difference = differenceBetweenPaces(min, max) + 1
        const minInSeconds = paceToSeconds(min)

        const table = document.getElementById('pace_chart_results')
        let newTable = "<thead><tr><th>Pace</th><th>Speed</th>"

        const distances = Array.from(document.querySelectorAll('.distance')).map(div => {
                const input = div.querySelector('input[type="checkbox"]')
                const checked = input.checked
                const kms = Number(input.dataset.kms)
                const label = div.querySelector('label')?.innerText ?? `${kms} kms`
                return {checked, kms, label}
        }).filter(({checked})=>checked);

        newTable += `${distances.map(({kms, label}) => `<th>${label}</th>`).join("")}</tr></thead><tbody>`

        const paces = [...new Array(+difference)].map(function(_,i) { return i + minInSeconds })
        const result = paces.map(function(paceInSeconds) {
            const pace = secondsToTime(paceInSeconds).valueWithQuotes
            const speed = paceInSecondsToSpeed(paceInSeconds)
            const row = distances.map(({kms}) => `<td>${paceInSecondsToFinishTime(paceInSeconds, kms)}</td>`).join("")

            newTable += `<tr><th>${pace}</th><th>${speed} km/h</th>${row}</tr>`
        })

        newTable += "</tbody>"
        table.innerHTML = newTable
    }

    document.querySelectorAll('#pace_chart_info input').forEach(el => {
        el.addEventListener('input', paceChartInputChange)
    })
    if (fastestPace.value || slowestPace.value) {
        paceChartInputChange()
    }
</script>

# Race sheet

<div id="race_sheet_info">
<div>
        <label for="race_sheet_distance">Distance:</label>
        <select name="race_sheet_distance" id="race_sheet_distance">
                <option value="marathon">Marathon</option>
                <option value="half_marathon">Half-marathon</option>
                <option value="custom">Custom distance</option>
        </select>
        <input type="number" step="0.1" id="race_sheet_custom_distance" placeholder="Custom distance" value="10"/>
</div>
<div><label for="race_title">Race title (optional):</label> <input type="text" id="race_title" name="race_title" placeholder="City, race name, etc" /></div>
<div><label for="race_date">Race date:</label> <input type="date" id="race_date" name="race_date" /></div>
<div><label for="pace_goal">Pace goal on the watch:</label> <input pattern="\d{1,2}:\d{2}" type="text" id="pace_goal" value="4:54"/></div>
<div><label for="bib_number_pickup_date">Bib number pickup date and time:</label> <input type="datetime-local" id="bib_number_pickup_date" name="bib_number_pickup_date" /></div>
<div>
<label for="nutrition_plan"><a href="https://www.maurten.com/fuelguide/">Nutrition plan</a> (50g+ of carbohydrates per hour is the recommendation, or better, <a href="https://youtu.be/mu7celO4IEE?t=237">your body weight = 75 kgs→eat 75 grams</a>):</label><br />
<textarea id="nutrition_plan" name="nutrition_plan" rows="5" cols="50">
1x Gel Caf 100 - 5 mins avant course
1x Gel 100     - au KM 4.5 (eau à 5.5)
1x Gel 100     - au KM 9 (eau à 10)
</textarea>
</div>
<div>
<label for="todo_items">TODO items:</label><br />
<textarea id="todo_items" name="todo_items" rows="5" cols="50">
Couper ongles
Se faire une belle moustache
Prévoir le trajet pour se rendre à la course
Prévoir des vieux vêtements chauds d'avant course, to give away
Si je dors à l'hôtel, emmener mon propre dîner pour samedi soir (pâtes) et petit-déj du dimanche
Samedi : shake-out run avec 1km à allure ~24h avant la course (donc le matin)
Samedi : charger montre et la reboot
Samedi : accrocher bib number au dossard et tout préparer pour être prêt à partir le dimanche matin
Samedi soir : préparer le petit déjeuner
Dimanche matin : petit-déj AU MOINS 2h avant la course
Dimanche matin : noter dans la main
  Warm up
  Montre en mode pace sur distance un peu plus longue
  Water/Feeding station/WC: Km 5, 10, etc
Dimanche matin : prendre casquette, lunettes, HRM chest strap, manchons
</textarea>
</div>
</div>

<div id="race_sheet" style="border: 1px solid gray; padding: 10px;">
        <h2 style="text-align:center">Unknown race - unknown date</h2>
        <p style="text-align:center;font-style:italic" id="race_sheet_subtitle"></p>
        <h3>Pace goal on the watch = <span id="race_pace_goal">?</span></h3>
        <ul>
                <li>Projected finish time with no margin of error (100% accurate GPS): <strong><span id="race_pace_goal_finish_time">?</span></strong></li>
                <li>Projected finish time with a <span id="race_pace_goal_finish_time_margin_error">?</span></li>
                <li>Speed: <strong><span id="race_pace_goal_speed">/</span> km/h</strong></li>
        </ul>
        <h3>Nutrition</h3>
        <ul id="race_sheet_nutrition">
        </ul>
        <h3>TODO until the race</h3>
        <ul id="race_todo" style="list-style-type:none;">
                <li id="race_bib_pickup_date_li"><input type="checkbox">Bib number pickup: <span id="race_bib_pickup_date">Sat 4 Mai</span></li>
        </ul>
</div>
<button onclick="printSheet()">Print the sheet</button>

<script>
    function raceSheetInputChange() {
        const preDefinedDistances = {
                'marathon': [42.195, 'Marathon'],
                'half_marathon': [21.0975, 'Half-marathon']
        }

        const raceCustomDistance = document.querySelector('#race_sheet_custom_distance')
        const raceDistance = document.querySelector('#race_sheet_distance').value
        const customRaceDistanceSelected = raceDistance === 'custom'
        raceCustomDistance.style = customRaceDistanceSelected ? '' : 'display:none';
        const [kms, label] = customRaceDistanceSelected ? [Number(raceCustomDistance.value), "Race"] : preDefinedDistances[raceDistance]

        // Sheet title and subtitle
        const dateValue = document.querySelector('#race_date').value
        const date = dateValue ? new Date(dateValue).toDateString() : 'unknown date'
        const title = document.querySelector('#race_title').value?.trim()
        let sheetTitle = label
        if (title) {
                sheetTitle += ` - ${title}`
        }
        document.querySelector('#race_sheet h2').innerText = `${sheetTitle} - ${date}`

        // Race distance
        document.querySelector('#race_sheet_subtitle').innerHTML = `${kms} kms`

        // Pace goal
        const paceGoal = document.querySelector('#pace_goal').value
        if (matchPaceFormatWithColon(paceGoal)) {
                const paceInSeconds = paceToSeconds(paceGoal)

                document.querySelector('#race_sheet #race_pace_goal').innerText = secondsToTime(paceInSeconds).valueWithQuotes
                document.querySelector('#race_sheet #race_pace_goal_finish_time').innerHTML = paceInSecondsToFinishTime(paceInSeconds, kms)
                const marginOfErrorInPercent = 1.5
                const marginOfError = ((marginOfErrorInPercent + 100) / 100).toFixed(3)
                const kmsWithError = (kms*marginOfError).toFixed(3)
                document.querySelector('#race_pace_goal_finish_time_margin_error').innerHTML = `${marginOfErrorInPercent}% margin of error (${kmsWithError} kms): <strong>${paceInSecondsToFinishTime(paceInSeconds, kmsWithError)}</strong>`
                document.querySelector('#race_sheet #race_pace_goal_speed').innerText = paceInSecondsToSpeed(paceInSeconds)
        }

        // Nutrition
        const nutritionItems = document.querySelector('#nutrition_plan').value.split("\n").map(e => e.trim()).filter(Boolean)
        const raceNutritionList = document.querySelector('#race_sheet_nutrition')
        raceNutritionList.innerHTML = ''
        nutritionItems.forEach(line => {
                const li = document.createElement('li')
                li.innerText = line
                raceNutritionList.appendChild(li)
        })

        // TODO list
        const todoItems = document.querySelector('#todo_items').value.split("\n").map(e => e.trimEnd()).filter(Boolean)
        const raceTodoList = document.querySelector('#race_todo')
        Array.from(raceTodoList.children).forEach(li => {
                if (!li.matches('#race_bib_pickup_date_li')) {
                        li.remove();
                }
        })
        let parentLi, currentUl = raceTodoList, rootUl = raceTodoList
        todoItems.forEach(line => {
                const li = document.createElement('li')

                if (line.startsWith(' ') && parentLi) {
                        if (currentUl === rootUl) {
                                currentUl = document.createElement('ul')
                                parentLi.appendChild(currentUl)
                        }
                }
                else {
                        currentUl = rootUl
                }

                li.innerHTML = `<input type="checkbox">${line}`
                currentUl.appendChild(li)
                parentLi = li
        })

        // Bib pickup date
        const bibNumberPickupDateValue = document.querySelector('#bib_number_pickup_date').value
        const bibNumberPickupDate = bibNumberPickupDateValue ? new Date(bibNumberPickupDateValue) : 'unknown date'
        document.querySelector('#race_bib_pickup_date').innerText =
                typeof bibNumberPickupDate === 'string'
                ? bibNumberPickupDate
                : `${bibNumberPickupDate.toDateString()}, ${bibNumberPickupDate.toLocaleTimeString()}`
    }

    document.querySelectorAll('#race_sheet_info input, #race_sheet_info select, #race_sheet_info textarea').forEach(el => {
        el.addEventListener('input', raceSheetInputChange)
    })
    raceSheetInputChange()

    window.printSheet = () => {
        const elem = document.querySelector('#race_sheet');
        const printWindow = window.open('', 'PRINT', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Race sheet</title>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(elem.innerHTML);
        printWindow.document.write('</body></html>');

        printWindow.print();
        printWindow.close();

        return true;
    }
</script>
