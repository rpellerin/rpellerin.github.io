Title: Living in Germany: How to buy an apartment in Berlin
Date: 2022-04-24 19:00
Modified: 2022-09-21 12:15
Category: Miscellaneous
Tags: berlin, germany, apartment
Slug: living-in-germany-how-to-buy-an-apartment-in-berlin
Authors: Romain Pellerin
Summary: Some tips and tricks about buying real estate in Berlin

There's already plenty of resources online. Specifically, there is [this unique priceless PDF]({static}/extra/Buying_an_Apartment_in_Germany.pdf) that I found on [Reddit](https://www.reddit.com/r/berlin/comments/n5c5jl/how_to_buy_an_apartment_in_germany_focuses_on/), which covers pretty much everything in detail.

For French speakers, here is a short video that explains in 10 minutes the steps to buy an apartment in Germany.

<iframe width="700" height="394" src="https://www.youtube-nocookie.com/embed/8lN4lKSqFSM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

But here I wanted to write a summary/short article, with the key facts and steps, about buying an apartment in Berlin. Here we go!

# 1. Know what you can afford

## The one-time payment for the apartment and the side costs

Ask around you. Ask loan brokers. Use online tools, such as [this one from Hypofriend](https://hypofriend.de/en/criteria/start).

You must be able to afford the invoices for Notar + Grundbucheintrag (~2% of the price of the apartment), Grunderwerbsteuer (~6%) and Maklerprovision (usually 3,57% in Berlin at the time of writing) with your equity capital/own money (Eigenkapital). The loan can only cover the price of the apartment (Kaufpreis). Up to 100% of the Kaufpreis can be covered by the loan, depending on the bank. You'll get the best interest rates though if you can finance 20% of the Kaufpreis with your Eigenkapital.

A good rule of thumb I have once been told is that most banks will generally accept to finance up to 110 times your monthly net income. Say you earn 3,000 euros net per month, you could in theory borrow up to 330,000 euros.

## The monthly cost of being a home-owner in Germany

As the owner of an apartment, you will have to pay the Hausgeld: it's money used for the common parts. It usually varies between 3 and 4 euros per square meter. For each apartment, you can get this piece of information directly on the listing or during the viewing.

On top of that, you need to pay a new tax: the Grundsteuer. It's pretty cheap in Berlin.

[More on the topic here.](https://www.thelocal.de/20210930/explained-the-hidden-costs-of-buying-a-house-in-germany/)

## Recap

### Loan needed

<style>
    table { border-collapse: collapse; margin: auto; }
    table.right { text-align: right }
    table.stripes tbody tr:nth-child(even) { background: #DDD; }
    thead { background: gray }
    thead.sticky { position: sticky; top: 0; }
    th,td { border: 1px solid black; }
    tr.red { background: red }
    tr.green { background: green }
    tr.purple { background: purple }
    input:invalid { outline: 1px solid red; }
    .results {
        margin: 5px 0;
        text-align: center;
        font-weight: bold;
        padding: 10px;
        background: rgba(252, 3, 3, 0.5);
    }
</style>
<div><input id="maklerprovisionfrei" type="checkbox" /><label for="maklerprovisionfrei">No broker commission</label></div>
<table style="border: 1px solid black">
<thead>
<tr>
    <th>Name</th>
    <th>Expenditure</th>
    <th>Capital</th>
</tr>
</thead>
<tbody>
<tr class="green">
    <td><strong>Equity capital</strong></td>
    <td></td>
    <td><input step=".01" value="20000" id="capital" type="number" placeholder="Money on your bank account" /></td>
</tr>
<tr class="red">
    <td><strong>Repair costs</strong></td>
    <td><input step=".01" id="repair" type="number" placeholder="New kitchen, etc" /></td>
    <td></td>
</tr>
<tr class="green">
    <td><strong>Sum capital - repair</strong></td>
    <td></td>
    <td id="capitalMinusRepair"></td>
</tr>
<tr class="red">
    <td>Tax (Grunderwerbsteuer 6%)</td>
    <td id="grunderwerbsteuer"></td>
    <td></td>
</tr>
<tr class="red">
    <td>Notar (Notarkosten 1,5%)</td>
    <td id="notar"></td>
    <td></td>
</tr>
<tr class="red">
    <td>Notar (Grundbucheintrag 0,5%)</td>
    <td id="grundbucheintrag"></td>
    <td></td>
</tr>
<tr class="red">
    <td>Broker commision (usually 3,57%)</td>
    <td id="maklerprovision"></td>
    <td></td>
</tr>
<tr class="red">
    <td><strong>Sum Tax + Notar + Broker commision</strong></td>
    <td id="sumTaxNotarMakler"></td>
    <td></td>
</tr>
<tr class="red">
    <td><strong>Purchase price</strong></td>
    <td><input step=".01" id="kaufpreis" type="number" value="100000" /></td>
    <td></td>
</tr>
<tr class="red">
    <td><strong>Total price (Purchase price + Tax + Notar + Broker commision)</strong></td>
    <td id="totalPrice"></td>
    <td></td>
</tr>
<tr class="green">
    <td><strong>Remaining capital for the apartement alone</strong></td>
    <td></td>
    <td id="remainingCapital"></td>
</tr>
<tr class="purple">
    <td><strong>Loan needed</strong></td>
    <td></td>
    <td id="loan"></td>
</tr>
<tbody>
</table>

### Profitability comparator

<div><input step=".01" id="rent" type="number" placeholder="Rent + Nebenkosten" value="1000" /> <label for="rent">Current rent (Warmmiete)</label></div>
<div><input step=".01" id="hausgeld" type="number" placeholder="Hausgeld" value="100" /> <label for="hausgeld">Hausgeld in the new apartment</label></div>
<div><input step=".01" id="grundsteuer" type="number" placeholder="Grundsteuer" value="30"/> <label for="grundsteuer">Expected Grundsteuer per month</label></div>

<div id="profitability" class="results"></div>

<script>
function computeProfitability({loanAmount, rate, interestRate, sumTaxNotarMakler}) {
    const rent = +(document.querySelector('input#rent').value || 0)
    const hausgeld = +(document.querySelector('input#hausgeld').value || 0)
    const grundsteuer = +(document.querySelector('input#grundsteuer').value || 0)

    const profitabilityDiv = document.querySelector('#profitability')

    let sumPaidInOldApartment = 0
    let sumPaidInNewApartment = sumTaxNotarMakler

    let remainingDebt = loanAmount
    let currentMonth = 0
    const rows = []
    const rowZero = document.createElement('tr')

    const rowZeromonthNumber = document.createElement('td')
    rowZeromonthNumber.innerHTML = currentMonth
    const rowZeroAccruedRents = document.createElement('td')
    rowZeroAccruedRents.innerHTML = toCurrency(sumPaidInOldApartment)
    const rowZeroNewApartmentCosts = document.createElement('td')
    rowZeroNewApartmentCosts.innerHTML = toCurrency(sumPaidInNewApartment)

    rowZero.appendChild(rowZeromonthNumber)
    rowZero.appendChild(rowZeroAccruedRents)
    rowZero.appendChild(rowZeroNewApartmentCosts)

    rows.push(rowZero)
    while(sumPaidInOldApartment < sumPaidInNewApartment) {
        currentMonth++
        if (currentMonth > 12 * 50) {
            // No need to process profitability beyond 50, abort...
            profitabilityDiv.innerHTML = `Your investment becomes profitable in more than 50 years...`
            return
        }
        const paidInterest = roundToTwo((remainingDebt * interestRate) / 12)
        const paidDebt = Math.min((rate - paidInterest), remainingDebt)
        remainingDebt = Math.max(remainingDebt - paidDebt)

        sumPaidInOldApartment += rent

        const row = document.createElement('tr')

        const rowMonthNumber = document.createElement('td')
        rowMonthNumber.innerHTML = currentMonth
        const accruedRents = document.createElement('td')
        accruedRents.innerHTML = toCurrency(sumPaidInOldApartment)
        const rowNewApartmentCosts = document.createElement('td')
        rowNewApartmentCosts.innerHTML = `${toCurrency(sumPaidInNewApartment)} + ${toCurrency(paidInterest)} + ${toCurrency(hausgeld)} + ${toCurrency(grundsteuer)}`

        row.appendChild(rowMonthNumber)
        row.appendChild(accruedRents)
        row.appendChild(rowNewApartmentCosts)

        rows.push(row)

        sumPaidInNewApartment += paidInterest + hausgeld + grundsteuer
        rowNewApartmentCosts.innerHTML += ` = ${toCurrency(sumPaidInNewApartment)}`
    }
    profitabilityDiv.innerHTML = `<p>With a monthly payment of ${toCurrency(rate)} (<a href="#rate">adjust</a>) and an interest rate of ${interestRate * 100}% (<a href="#interests">adjust</a>)...</p><details>
        <summary>Your investment becomes profitable on the ${currentMonth}th month (year ${roundToTwo(currentMonth/12)})</summary>
        <table id="profitability_table">
            <thead>
            <tr>
                <th>Month</th>
                <th>Accrued rents</th>
                <th>Tax + Notar + Broker +<br />Accrued Interests + Hausgeld + Grundsteuer</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </details>`

    const profitabilityTbody = document.querySelector('#profitability_table tbody')
    profitabilityTbody.append(...rows)
}

function roundToTwo(num) {
  return +(Math.round(num + "e+2") + "e-2");
}

function toCurrency(num) {
return `${roundToTwo(num).toLocaleString()} €`
}

const multiply = (a,b) => (a*b)

function computeLoanTable({loanAmount, rate, interestRate, sondertilgung}) {
    const capital = +(document.querySelector('input#capital').value || 0)
    const repair = +(document.querySelector('input#repair').value || 0)
    const kaufpreis = +(document.querySelector('input#kaufpreis').value || 0)
    const maklerprovisionfrei = document.querySelector('input#maklerprovisionfrei').checked
    const capitalMinusRepair = capital - repair
    document.querySelector('#capitalMinusRepair').innerHTML = toCurrency(capitalMinusRepair)
    const tax = roundToTwo(multiply(kaufpreis, 0.06))
    document.querySelector("#grunderwerbsteuer").innerHTML = toCurrency(tax)
    const notar = roundToTwo(multiply(kaufpreis, 0.015))
    document.querySelector("#notar").innerHTML = toCurrency(notar)
    const grundbucheintrag = roundToTwo(multiply(kaufpreis, 0.005))
    document.querySelector('#grundbucheintrag').innerHTML = toCurrency(grundbucheintrag)
    const makler = maklerprovisionfrei ? 0 : roundToTwo(multiply(kaufpreis,0.0357))
    document.querySelector("#maklerprovision").innerHTML = toCurrency(makler)
    const sumTaxNotarMakler = roundToTwo(tax + notar + grundbucheintrag + makler)
    document.querySelector("#sumTaxNotarMakler").innerHTML = toCurrency(sumTaxNotarMakler)
    const totalPrice = kaufpreis + sumTaxNotarMakler
    document.querySelector("#totalPrice").innerHTML = toCurrency(totalPrice)
    const remainingCapital = capitalMinusRepair - sumTaxNotarMakler
    document.querySelector("#remainingCapital").innerHTML = toCurrency(remainingCapital)
    document.querySelector("#loan").innerHTML = toCurrency(kaufpreis - remainingCapital)

    computeProfitability({loanAmount, rate, interestRate, sondertilgung, sumTaxNotarMakler})
}
</script>

# 2. Make sure you have a SCHUFA Score, that is not too bad

This is especially true if you are a foreigner and do not have a German bank account, or barely use it. N26 can fuck things up and somehow not transmit any data to SCHUFA. Not having a SCHUFA Score is even worse than having a poor score, as some banks will simply not proceed at all with you, not even studying your case, since they can't get any score at all. [You can ask SCHUFA to provide you with the data they have about you by invoking the GDPR](https://myhelpbuddy.com/how-to-get-your-schufa-score-for-free/).

# 3. The apartment hunt

Start with [immobilienscout24](https://www.immobilienscout24.de/). Set an alert to get hourly emails. Discover agencies through the website. Then check those agencies' websites. Here are some:

- [www.immo-boerse.com/immobilien/](https://www.immo-boerse.com/immobilien/)
- [www.next-estate.de/de/](https://www.next-estate.de/de/)
- [www.engelvoelkersberlin.com/](https://www.engelvoelkersberlin.com/)
- [bepartofberlin.de/](https://bepartofberlin.de/)

Set alerts on each website. Be on the lookout for "Vermietet", which means "rented". Surely you don't want to buy an apartment where there's already a tenant with an unlimited contract...

Whenever you see a listing of interest, send an email. After 6 hours without any reply, call them directly. For each apartment, they should send you back a document presenting the property called an "Exposé". Tell them straight away what your availabilities are for a viewing.

# 4. Go to viewings

If the Exposé does not contain any NO-GO information (like a crazy Energieklasse...), move forward and go to the viewing.

The PDF I mentioned in the introduction has some great pieces of advice as to what to look for. For instance, mold on the walls/ceiling is a no go, just like single-glazed windows are. Pay attention to the Energieklasse. Be quiet for a few seconds, make sure the neighborhood is not noisy (metro, car traffic, trams, schools, etc). Assess how much repair is needed. Ask about:

- Hausgeld
- New owners, old owners, what the WEG (Wohnungseigentümergemeinschaft - homeowner association) is made of.
- Which walls can be torn down
- How freely you can change the windows, doors, etc

# 5. You found one you like? It's in your price range? Reserve it!

This step mostly depends on your Makler. Some will ask for a bunch of papers to sign + ID card copies to send, some others will also want to see a proof that you have fund and a bank backing you up. Unless stated in the PDF aforementioned, I never heard of anyone having to make some down payment/pay a reservation fee ahead.Just reach out to your Makler and ask how to reserve it.

# 6. Money time: the loan

## How to get a loan

You know how much the Kaufpreis of what you reserved is. You should be able to calculate the price you'll have to pay for the Notar, Grundbucheintrag, Grunderwerbsteuer and Maklerprovision (sometimes this one is "free"). You also know your Eigenkapital. Estimate the repair costs (new kitchen, new floor, etc). Do the maths of that all and you'll know how much money you need to borrow.

Now, 2 options:

1. Go see the banks yourself
2. Use a mortage broker

If your German is good enough and you have plenty of time, 1. is the best option, as you'll likely get the best interest rate. With 2., you will **likely** get a worse deal, cause even though you don't need to pay the mortage broker, the bank will (the broker usually get a fixed percent of the loan). So in theory, banks will "compensate" without telling you.
But with 2., you'll also save a shit ton of time and hassle. Mortgage brokers are experienced, they know how to solve many tricky situations (no Schufa score...). Not to mention that brokers who speak your mother tongue are a HUGE HELP. They can offer additionnal services, such as acting as an official translator during the Notar meeting, probably free of charges.

For expats, [Hypofriend](https://hypofriend.de/en) has been gaining a lot of traction these past few years, as they offer all their services online and in English. For French speakers, [www.connexion-francaise.com](https://www.connexion-francaise.com) and [expatriation-allemagne.com/](https://expatriation-allemagne.com/) are good options.

Most banks in Germany will want to secure their investement with a mortage deed (Grundschuldbestellungsurkunde). More on that in the next chapter.

## Understand your loan offers

**After the loan is signed, you have 14 days to rescind (cancel) it. It is therfore very important to go to the notary to sign the Kaufvertrag (purchase contract) within these 14 days. Otherwise, should the purchase never happen, you would not be able to cancel the loan.**

- "Sollzinssatz" is the interest rate.
- "Monatliche Rate" is what you pay each month out of your bank account: this is the sum of the interests and the repayment
- "Tilgungssatz" (also called "Sparrate") is the repayment rate

In Germany, the most common type of loan is with a fixed-interest period (usually 10 or 15 years) after which you can either renegociate a new interest, pay the whole remaining debt back, or continue the loan with another bank (and another interest rate, most likely).

Almost all banks allow exceptional repayments (Sondertilgung) every year, up to 5% of the loan. For instance, the Deutsche Bank allow you those Sondertilgung after the first 12 months in the loan, up to 5% of the loan per calendar year.

You will agree with your bank on a Monatliche Rate, the amount of money that comes out of your bank account every month. In this amount, you pay both interests and debt back. The Monatliche Rate is always the same until the end of the agreed fixed-interest period.

Each month, you will pay back ((INTEREST RATE \* REMAINING DEBT) / 12) in interests, and you will pay back (MONATLICHE RATE - INTERESTS PAID) as debt repayment.

For instance, the first month, for a loan of 400,000 euros with an interest of 2%, and a monatlich Rate of 2,000 euros, you will pay

- In interests: (0.02 \* 400,000) / 12 = 666.67 euros
- In debt: 2,000 - 666.67 euros = 1333.33 euros

The next month, you will pay:

- In interests: (0.02 \* (400,000 - 1333.33)) / 12 = 664.44 euros
- In debt: 2,000 - 664.44 euros = 1335.56 euros

## Simulation: Tilgungsplan

<div><input step=".01" type="number" value="400000" id="loan_amount"/> <label for="loan_amount">Loan</label></div>
<div><input step=".01" type="number" placeholder="2" value="2" id="interests"/> <label for="interests">Interests rate (%)</label></div>
<div><input step=".01" type="number" placeholder="2000" value="2000" id="rate"/> <label for="rate">Monatliche Rate</label></div>
<div><input step=".01" type="number" placeholder="0" value="0" id="sondertilgung"/> <label for="sondertilgung">Sondertilgung (one payment every 12 months, after 1 year)</label></div>

<div id="summary" class="results"></div>

<table id="tilgungsplan" class="stripes right">
<thead class="sticky">
<tr>
    <th>Date</th>
    <th>#</th>
    <th>Repayment</th>
    <th>Interest</th>
    <th>Total paid this far</th>
    <th>Remaining debt</th>
    <th>Debt repaid</th>
    <th>Interests paid</th>
    <th>Sonder-tilgung</th>
</tr>
</thead>
<tbody>
</tbody>
</table>

<script>
function INTERESTS_FOR(remainingDebt, monthlyPayment, interestPercent, sondertilgung, sondertilgungEveryXMonths = 0, stopAfterMonth, currentMonth = 1) {
  if (remainingDebt === 0 || currentMonth > stopAfterMonth) return [0,0,remainingDebt]
  const paidInterest = roundToTwo((remainingDebt * interestPercent) / 12)
  const paidDebt = Math.min((monthlyPayment - paidInterest), remainingDebt)
  const paidAnticipatedPayment = currentMonth > 12 && sondertilgungEveryXMonths > 0 && (currentMonth - 1) % sondertilgungEveryXMonths === 0 ? sondertilgung : 0
  const newRemainingDebt = Math.max(remainingDebt - paidDebt - paidAnticipatedPayment)
  const [totalDebtPaid, totalInterestsPaid, debtLeftToPay] = INTERESTS_FOR(newRemainingDebt, monthlyPayment, interestPercent, sondertilgung, sondertilgungEveryXMonths, stopAfterMonth, currentMonth + 1)
  return [totalDebtPaid + paidDebt, totalInterestsPaid + paidInterest, debtLeftToPay]
};



function computeAll() {
    const loanAmount = +(document.querySelector('#loan_amount').value || 0)
    const rate = +(document.querySelector('#rate').value || 0)
    const interestRate = +(document.querySelector('#interests').value || 0) / 100
    const sondertilgung = +(document.querySelector('#sondertilgung').value || 0)

    const tBody = document.querySelector('#tilgungsplan tbody')
    tBody.innerHTML = null

    computeLoanTable({loanAmount,
        rate,
        interestRate,
        sondertilgung})

    const [ totalDebtPaid, totalInterestsPaid, debtLeftToPay ] = INTERESTS_FOR(
        loanAmount,
        rate,
        interestRate,
        sondertilgung,
        12,
        120)
    document.querySelector('#summary').innerHTML = `After 10 years, you would have...
        <ul>
          <li>Paid ${toCurrency(totalInterestsPaid)} in interests</li>
          <li>Paid ${toCurrency(totalDebtPaid)} in debt back</li>
          <li>${toCurrency(debtLeftToPay)} still to pay</li>
        </ul>
    `

    let remainingDebt = loanAmount
    let interestsPaid = 0
    let currentRowDate = new Date()
    let totalPaid = 0
    for (let currentMonth = 0; true; currentMonth++) {
        if (currentMonth > 12 * 50) {
            // No need to process a loan of more than 50 years, abort...
            tBody.innerHTML = null
            window.alert(`The Monatliche Rate (${rate}) has to be higher, otherwise your loan will last over 50 years`)
            return
        }

        const tr = document.createElement('tr')

        const dateTd = document.createElement('td')
        const monthTd = document.createElement('td')
        const repaymentTd = document.createElement('td')
        const interestTd = document.createElement('td')
        const totalTd = document.createElement('td')
        const remainingDebtTd = document.createElement('td')
        const debtRepaidTd = document.createElement('td')
        const interestPaidTd = document.createElement('td')
        const sondertilgungTd = document.createElement('td')

        dateTd.innerHTML = currentRowDate.toLocaleDateString()
        currentRowDate.setMonth(currentRowDate.getMonth() + 1);
        currentRowDate.setDate(1);
        monthTd.innerHTML = currentMonth

        const interestsToPay = roundToTwo(currentMonth === 0 ? 0 : multiply(remainingDebt, interestRate) / 12)
        let sonderTilgungToPay = currentMonth > 12 && (currentMonth - 1) % 12 === 0 ? sondertilgung : 0
        const debtToPay = currentMonth === 0 ? 0 : Math.min(rate - interestsToPay, remainingDebt)

        repaymentTd.innerHTML = toCurrency(debtToPay)
        interestTd.innerHTML = toCurrency(interestsToPay)
        remainingDebt -= currentMonth === 0 ? 0 : debtToPay
        if (remainingDebt < sonderTilgungToPay) {
            sonderTilgungToPay = remainingDebt
            remainingDebt = 0
        }
        else {
            remainingDebt -= sonderTilgungToPay
        }
        totalPaid += (interestsToPay + debtToPay + sonderTilgungToPay)
        totalTd.innerHTML = toCurrency(totalPaid)
        remainingDebtTd.innerHTML = toCurrency(remainingDebt)
        interestsPaid = interestsPaid + interestsToPay
        interestPaidTd.innerHTML = toCurrency(interestsPaid)
        debtRepaidTd.innerHTML = toCurrency(totalPaid - interestsPaid)
        sondertilgungTd.innerHTML = toCurrency(sonderTilgungToPay)


        tr.appendChild(dateTd)
        tr.appendChild(monthTd)
        tr.appendChild(repaymentTd)
        tr.appendChild(interestTd)
        tr.appendChild(totalTd)
        tr.appendChild(remainingDebtTd)
        tr.appendChild(debtRepaidTd)
        tr.appendChild(interestPaidTd)
        tr.appendChild(sondertilgungTd)
        tBody.appendChild(tr)
        if (remainingDebt <= 0) break
    }
}

computeAll(); // first loading
document.querySelectorAll('input').forEach(i => i.addEventListener('input', computeAll))

</script>

# 7. Kaufvertrag (Purchase contract)

While you are reviewing the loan offer, you should start thinking about the Kaufvertrag. If the seller gives you the freedom to pick the notary of your choice, choose one who will also edit a copy of the contract in English.

If you German is not excellent, the notary will probably require that a translator is present during the meeting. Your mortgage broker might be able to take the job. A sworn translator is not always required, as long as the notary is convinced you will understand 100%. Put your translator and the notary in contact.

The notary will typically send the first draft of the contract over email after a few days. Review it thoroughly. Have it translated via [DeepL](https://www.deepl.com/translator) if need be. If you buy with somebody, you might want to officially state in the contract how much each of you owns of the apartment. Tell that to the notary, so that it gets written in the contract.

Your notary appointment cannot take place before 2 weeks after the last draft of the contract has been edited (legal requirement).

A few days ahead of the notary meeting, the notary will ask you a bunch of papers from your financing bank, so that they can start writing the draft of the Grundschuldbestellungsurkunde (mortgage deed). Forward that request to your mortgage broker if you have one.

The actual meeting is pretty boring: the notary will read EXACTLY AS WRITTEN first the Kaufvertrag, in the presence of the seller and your translator, and then the Grundschuldbestellungsurkunde only with you and your translator. That's all. Nothing more, nothing less. Expect 2 hours.

# 8. Payments

A few days later, you will receive various invoices from the Notar (Notarkosten at least, maybe the Grundbucheintrag too).

**Another few days later, the Notar will ask you to pay the apartment before a given due date.** What you'll most likely do is, transfer your equity capital to some temporary bank account provided by your financing bank. Then, the bank will pay the seller. The bank might ask you to transfer all of the equity capital you said you had, minus what you paid to the Notar already, and then ask you to pay the remaining future invoices (Grunderwerbsteuer at least) from that temporary bank account.

# Conclusion: timeline of events

<figure class="center"><img alt="All the events and when then take place" src="{static}/images/buying_an_apartment_in_berlin.png" /></figure>

# Great how-to articles about buying in Berlin

## In English

- [www.settle-in-berlin.com/buy-apartment-berlin-in-5-steps/](https://www.settle-in-berlin.com/buy-apartment-berlin-in-5-steps/)

## In French

- [www.goodmorningberlin.com/financer-achat-immobilier-a-berlin/](https://www.goodmorningberlin.com/financer-achat-immobilier-a-berlin/)
- [aberlin.fr/acheter-un-appartement-a-berlin.php](https://aberlin.fr/acheter-un-appartement-a-berlin.php)
- [guide.mfc.bayern/Acheter_de_l%27immobilier](https://guide.mfc.bayern/Acheter_de_l%27immobilier)
- [www.guidewebimmobilier.com/annonce-immobiliere/achat-appartement-berlin-quil-faut-savoir.html](https://www.guidewebimmobilier.com/annonce-immobiliere/achat-appartement-berlin-quil-faut-savoir.html)
- [expatriation-allemagne.com/categorie/nos-expertises/immobilier/](https://expatriation-allemagne.com/categorie/nos-expertises/immobilier/)
- [www.connexion-francaise.com/vivre-en-allemagne/acheter-un-bien-immobilier-en-allemagne](https://www.connexion-francaise.com/vivre-en-allemagne/acheter-un-bien-immobilier-en-allemagne)

# From Reddit

- [To people who own their flats in Berlin, which tips would you give your past-self, before your first purchase?](https://www.reddit.com/r/berlin/comments/pgfd3z/to_people_who_own_their_flats_in_berlin_which/)
- [Buying and owning an apartment in Berlin](https://www.reddit.com/r/eupersonalfinance/comments/uhccds/buying_and_owning_an_apartment_in_berlin/)
- [Buying an apartment in Berlin (2021, Covid)](https://www.reddit.com/r/berlin/comments/m2ldbw/buying_an_apartment_in_berlin_2021_covid/)
