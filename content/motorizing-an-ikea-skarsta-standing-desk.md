Title: Motorizing an IKEA Skarsta Standing Desk
Date: 2023-03-14 23:00
Modified: 2023-04-29 14:00
Category: Miscellaneous
Tags: hacking, standing desk, diy
Slug: motorizing-an-ikea-skarsta-standing-desk
Authors: Romain Pellerin
Summary: Tutorial about how I motorized my IKEA Starska standing desk

**UPDATE March 2024: the V2 of this project is finally coming together. Scroll all the way down to read about it!**

I got really tired of turning the crank of my IKEA Starska standing desk multiple times a day. Not only this is tedious, but also I can't keep on typing or using my mouse while doing so. And if I'm in a meeting, I look stupid. So I decided to motorize it. I know IKEA already sells an electrical version of it with a motor, but it's 200 euros more expensive and I already had one desk. Plus I like challenges!

After hours of googling and reading various blog posts (see the bottom of this page for links), after days spent waiting for all my orders to arrive, after minutes of tinkering, I finally got a working prototype!

<video controls>
  <source src="./videos/motorizing-an-ikea-skarsta-standing-desk.mp4" type="video/mp4">
</video>
 
That's far from perfect, I already have ideas about how to improve it, but for now that'll do... Down below is the shopping list for this project and the steps to build it. I hope this helps!

# Hardware

## DC 12V Gear Motor (37D mm)

- Not just a regular DC motor. See [the difference between a regular DC motor and a DC gear motor](https://electronics.stackexchange.com/questions/97477/difference-between-a-dc-motor-and-gear-motor)
- The diameter must be 37 mm, so that it fits perfectly under the IKEA Starska standing desk
- Torque must be equal to or greater than 2Nm (20.4 kg.cm) (info found [here](http://cesarmoya.com/blog/motorizing-standup-desk/)). Look at the stall torque, not the rated torque.

<figure class="center">
<img class="zoomable" src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/motor.jpg" alt="A picture of the motor" />
<figcaption>The motor I bought from Aliexpress</figcaption>
</figure>

I bought and tried these 2, [the 12V 72 RPM edition, that draws 3 amps ("stall current"), and the 12V 136 RPM one](https://de.aliexpress.com/item/32968002582.html). According to the specs of the 72 RPM one, the stall torque is greater than 30 Kg.cm and it works perfectly! I can put weight on the desk (I tried more than 30 Kg) and it still raises the desk up easily! As to the 136 RPM, it works well too, up to 30 Kg, but it gets real close to stalling. Without much weight (a laptop and a monitor), it raises the desk at about 100 rotations per minute.

<figure class="center">
<img class="zoomable" src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/motor-specs.jpg" alt="A table showing the specs of the motor" />
<figcaption>Specs of the motor</figcaption>
</figure>

Alternatively, [this other motor looks like a viable option too](https://www.pololu.com/product/4745).

## Aluminum L-Bracket for 37D Metal Gearmotors

<figure class="center">
<img class="zoomable" src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/l-bracket-for-37d-motors.jpg" alt="A picture of a pair of L-Brackets for motors" />
<figcaption>The L-Bracket pair I got (only 1 is necessary for this project though)</figcaption>
</figure>

We will use this to secure the motor to the desk. [I got it from The PiHut](https://thepihut.com/products/pololu-stamped-aluminum-l-bracket-pair-for-37d-metal-gearmotors).

## Power adapter INPUT AC 110-240V / OUTPUT DC 12V 5A

<figure class="center">
<img class="zoomable" src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/acdc-power-adapter.jpg" alt="A picture of a power adapter" />
<figcaption>The adaper I got from Amazon</figcaption>
</figure>

[I got this one from Amazon](https://www.amazon.de/12V-voltage-transformer-power-adapter/dp/B07L5GP7SD), works like a charm!

## 6mm hex key

<figure class="center">
<img class="zoomable" src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/6mm-hex-key.jpg" alt="A picture of an hex key" />
<figcaption>The 6mm hex key I got</figcaption>
</figure>

[I got this one Obi.de](https://www.obi.de/schluessel-abzieher/lux-sechskant-schluessel-comfort-6-mm/p/3471075) and I had to cut off the bent part of it. This will replace the original crank provided with the desk.

## 7mm shaft coupler

<figure class="center">
<img class="zoomable" src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/7mm-shaft-coupler.jpg" alt="A picture of a shaft coupler" />
<figcaption>The 7mm shaft coupler I got</figcaption>
</figure>

This will connect the motor to the hex key in the desk. Get a [7mm shaft coupler](https://www.amazon.de/dp/B07HKJL1XC), since the motor comes with a 6mm D-shaped shaft. The hex shaft for the desk is 6mm too, so the shaft coupler must be 1mm larger.

## DC motor driver

<figure class="center">
<img class="zoomable" src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/dc-motor-driver.jpg" alt="A picture of a DC motor driver" />
<figcaption>The DC motor driver I got from Cytron.io</figcaption>
</figure>

This will control the motor. I went with the [MD10-POT from Cytron](https://www.cytron.io/p-10-amp-7v-30v-potentiometer-and-switch-control-dc-motor-driver), that comes with a potentiometer (adjust speed) and switch (2 directions and stop), and that does not require any Arduino nor any code writing.

**Careful**: this is a regenerative motor driver ([as described at the bottom here](https://www.cytron.io/tutorial/md10-pot-controlling-dc-motor-without-writing-code)), meaning that when you stop powering the motor, it keeps spinning a bit before coming to a stop (due to inertia, while it's "braking"), and as a result the current flows back to the power source. As a consequence this driver should be used with a battery, not a switching power supply, because a battery can be charged but with a switching power supply the current has nowhere to go. For this project it is ok though as the desk is heavy and won't allow the motor to keep spinning when the power is cut.

Learn more about [controlling a DC motor here](https://www.cytron.io/tutorial/5-easiest-ways-control-dc-motor).

## DC Connector Barrel Plug Adapter 2.5mm x 5.5mm

<figure class="center">
<img class="zoomable" src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/dc-connector-barrel-plug-adapter.jpg" alt="A picture of a DC connector barrel plug adapter" />
<figcaption>The one I got</figcaption>
</figure>

This is used to connect the DC power supply with the motor driver, [I got this one from Amazon](https://www.amazon.de/dp/B09TB4D8ZT).

## Misc

- Duct tape
- A grinder, to cut off the bent part of the hex key
- Electrical wire

# How to build it

1. Cut off the bent part of the hex key with a grinder. We need a straight hex key.
1. Replace the original crank with the hex key, mounted with the shaft coupler.
1. Position the L-bracket with the motor at the end of the hex key and mark the final position with a pen.
1. Screw the L-bracket to the desk using a drill (slow speed)

    <figure class="center">
      <img src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/step-4.jpg" alt="A picture the L-bracket attached to the desk" />
      <figcaption>The L-bracket is now attached to the desk</figcaption>
    </figure>

1. Secure the motor to the L-bracket

    <figure class="center">
      <img src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/step-5.jpg" alt="A picture of the motor attached to the L-bracket" />
      <figcaption>The motor is now attached to the L-bracket</figcaption>
    </figure>

1. Attach the power supply to the desk with a self-adhesive velcro, or just tape:

    <figure class="center">
      <img src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/step-6a.jpg" alt="A piece of self-adhesive velcro" />
      <figcaption>Self-adhesive velcro</figcaption>
    </figure>
    <figure class="center">
      <img src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/step-6b.jpg" alt="A picture of velcro under the desk" />
      <figcaption>Velcro stuck to the desk</figcaption>
    </figure>
    <figure class="center">
      <img src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/step-6c.jpg" alt="A picture showing the power supply wrapped in velcro and attached to the desk" />
      <figcaption>The power supply wrapped in velcro</figcaption>
    </figure>

1. Connect the motor to the DC motor driver using wires, and tape the junctions for improved safety

    <figure class="center">
      <img src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/step-7a.jpg" alt="A picture showing the taped junctions between the motor and the wires" />
      <figcaption>Put tape around the connections</figcaption>
    </figure>

1. Connect the power supply to the DC motor driver (pay attention to polarity) using the barrel and wrap it in tape for improved safety

    <figure class="center">
      <img src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/step-8.jpg" alt="A picture showing the power supply connected to the DC motor driver using the barel" />
      <figcaption>The barrel, connecting the DC motor driver and power supply, wrapped in tape</figcaption>
    </figure>

1. And last, tape everything under the desk, make sure nothing is hanging. I protected some parts (soldered points) of the motor driver board with tape. I placed the switch button near the edge of the desk to reach it easily. A better design would be to put everything in a small plastic box.

    <figure class="center">
      <img class="zoomable big" src="{static}/images/motorizing-an-ikea-skarsta-standing-desk/step-9.jpg" alt="A picture showing the end result" />
      <figcaption>The end result</figcaption>
    </figure>

# A V2 of my project would be...

With a [different motor driver](https://www.cytron.io/c-motor-and-motor-driver/c-motor-driver/c-dc-motor-driver), that has "Over Current" and "Under Voltage" protections, such as the MD13S from Cytron. Also with an Arduino and auto-raise feature based on how long it takes to raise/lower it. I don't need to have multiple programmable positions, nor an OLED screen. An auto-raise/lower feature can be tricky, for the following reasons (copy/pasted from [https://github.com/cesar-moya/arduino-power-desktop/blob/master/MotorControl/MotorControl.ino](https://github.com/cesar-moya/arduino-power-desktop/blob/master/MotorControl/MotorControl.ino)):

> If you activate auto-raise, and your desk was already at the maximum height,
> then - depending on your desk - on the IKEA SKARSTA it will hit a stopping
> point and the MOTORS WILL STALL for the amount of seconds that you recorded.
> In other words, if you recorded 30 seconds to raise, and your desk is
> already at the top position (or close), and you still enable auto-raise, you
> risk damaging your motors as a full power will be sent to them but they will
> be blocked. When using auto-raise and auto-lower you must ALWAYS be present
> and watching the desk, ready to cancel the operation if the motors stall for
> any reason.

And finally, a nicer design, where everything is not taped underneath but put in a plastic box.

# Upgrade 2024: version 2!

For this V2, we're gonna reuse most of the what we used for the V1, but we'll also need additionally:

- [Arduino Uno R4 Minima - 18 euros](https://store.arduino.cc/products/uno-r4-minima)
- [2 push buttons - 7.99 euros for 7 buttons](https://www.amazon.de/dp/B0825RCZJS): one to raise the desk, one to lower it
- [2 10K-Ohm resistors - 0.05 euros each](https://www.berrybase.de/metallschichtwiderstand-10-0-kohm-1/4w-1-0207-axial-durchsteckmontage?c=168), for the buttons ([despite the Arduino having an internal pullup resistor that can be used](https://docs.arduino.cc/built-in-examples/digital/InputPullupSeriale), [it's not reliable](https://forum.arduino.cc/t/resistor-why/108705/2) [enough](https://forum.arduino.cc/t/push-buttons-and-resistors-why-and-how/584493)). Prefer metal film over carbon, supposedly they're more robust.
- [Pololu TB6612FNG motor driver - 4.20 euros](https://www.berrybase.de/pololu-tb6612fng-dualer-motortreiber)
- [A  200\*120\*55mm junction box - 12.34 euros](https://www.amazon.de/dp/B0983NSV6F)

The Arduino will be powered directly to its VIN pin through the 5V output pin of the motor driver. The power supply will supply directly the motor driver.

## A few words on the motor driver

A lot of online tutorials recommend using the classic L298N for Arduino projects. Hower, a lot of people report issues with it on the Arduino forum. A common reply is [that the L298N motor driver is old and obsolete](https://forum.arduino.cc/t/l298n-is-a-mess-helppp/903638), one of its flaws is the 5V drop. People often suggest using modern motor drivers from [Pololu](https://www.pololu.com/), and the [TB6612FNG](https://www.pololu.com/product/713) seemed like a good affordable option, even if it supports 2 motors and I'm only using it for one.

Why not L298N?

# Other tutorials

- [http://cesarmoya.com/blog/motorizing-standup-desk/](http://cesarmoya.com/blog/motorizing-standup-desk/): says 2Nm is needed for torque
- [https://github.com/aenniw/ARDUINO/tree/master/skarsta](https://github.com/aenniw/ARDUINO/tree/master/skarsta)
- [https://github.com/flosommerfeld/ESP8266-IKEA-Skarsta-Trotten-Web-Dashboard](https://github.com/flosommerfeld/ESP8266-IKEA-Skarsta-Trotten-Web-Dashboard)
- [https://www.instructables.com/Motorizing-an-IKEA-SKARSTA-Table/](https://www.instructables.com/Motorizing-an-IKEA-SKARSTA-Table/): they use an optional endstop to count how many rotations the motor does, thanks to some flag put on the shaft
- [https://hackcorrelation.blogspot.com/2015/09/ikea-skarsta-sitstanding-desk-hack.html](https://hackcorrelation.blogspot.com/2015/09/ikea-skarsta-sitstanding-desk-hack.html): says 2Nm is needed for torque
