Title: Motorizing an IKEA Skarsta Standing Desk
Date: 2023-03-14 23:00
Category: Miscellaneous
Tags: hacking, standing desk, diy
Slug: motorizing-an-ikea-skarsta-standing-desk
Authors: Romain Pellerin
Summary: Tutorial about how I motorized my IKEA Starska standing desk

**THIS IS A DRAFT ARTICLE**. Still WIP...

[Basic knowledge about DC motors here](https://www.cytron.io/tutorial/5-easiest-ways-control-dc-motor).

# Hardware

## DC 12V Gear Motor

- Not just a regular DC motor. See [the difference between a regular DC motor and a DC gear motor](https://electronics.stackexchange.com/questions/97477/difference-between-a-dc-motor-and-gear-motor)
- Torque must be equal or greater than 2Nm (20.4 kg.cm) (info found [here](http://cesarmoya.com/blog/motorizing-standup-desk/)). Look at the stall torque, not the rated torque.

[This one, in its 72 RPM edition looks good enough](https://de.aliexpress.com/item/32968002582.html?s...&gatewayAdapt=glo2deu). Alternatively, [this one should work too](https://www.pololu.com/product/4745).

## Power adapter INPUT AC 110-240V / OUTPUT DC 12V 5A

[This one on Amazon seems to do the job](https://www.amazon.de/12V-voltage-transformer-power-adapter/dp/B07L5GP7SD).

## 6mm Hex key or Hex shaft

[This one on Obi.de](https://www.obi.de/schluessel-abzieher/lux-sechskant-schluessel-comfort-6-mm/p/3471075).

Maybe also buy a shaft coupler 7mm-9mm, if the motor takes in a 10mm shaft for instance. The hex shaft of the desk is 6, so you must at least buy an adapter for 7mm+.

## DC motor driver

I'm going with the [MD10-POT from Cytron](https://www.cytron.io/p-10-amp-7v-30v-potentiometer-and-switch-control-dc-motor-driver), that comes with a potentiometer (adjust speed) and switch (2 directions and stop), and that does not require any Arduino nor code writing.

**Careful**: this is a regenerative motor driver ([as described at the bottom here](https://www.cytron.io/tutorial/md10-pot-controlling-dc-motor-without-writing-code)), meaning that when you stop powering the motor, it keeps spinning a bit before coming to a stop (due to inertia, while it's "braking"), and as a result the current flows back to the power source. As a consequence this driver should be used with a battery, not a switching power supply, because a battery can be charged but with a switching power supply the current has nowhere to go. In my case it should be ok as the desk is heavy and won't allow the motor to keep spinning.

## DC Connecter Barrel Plug Adapter 2.5mm x 5.5mm

[To connect the DC power supply with the motor driver](https://www.amazon.de/dp/B09TB4D8ZT).

## Shaft coupling

[To connect the motor with the wrench of the table](https://www.amazon.de/dp/B07PW4GKBM).

## Fuses

Do I need some?

# A V2 of my project would be...

With a [different motor driver](https://www.cytron.io/c-motor-and-motor-driver/c-motor-driver/c-dc-motor-driver), than has Over Current and Under Voltage protections, such as the MD13S from Cytron. Also with an Arduino and auto-raise feature based on how long it takes to raise/lower it. I don't need to have multiple programmable positions, nor an OLED screen. An auto-raise/lower feature can be tricky, for the following reasons (copy/pasted from [https://github.com/cesar-moya/arduino-power-desktop/blob/master/MotorControl/MotorControl.ino](https://github.com/cesar-moya/arduino-power-desktop/blob/master/MotorControl/MotorControl.ino)):

    > If you activate auto-raise, and your desk was already at the maximum height, 
    > then - depending on your desk - on the IKEA SKARSTA it will hit a stopping 
    > point and the MOTORS WILL STALL for the amount of seconds that you recorded. 
    > In other words, if you recorded 30 seconds to raise, and your desk is 
    > already at the top position (or close), and you still enable auto-raise, you 
    > risk damaging your motors as a full power will be sent to them but they will 
    > be blocked. When using auto-raise and auto-lower you must ALWAYS be present 
    > and watching the desk, ready to cancel the operation if the motors stall for 
    > any reason.

# Other tutorials

- [http://cesarmoya.com/blog/motorizing-standup-desk/](http://cesarmoya.com/blog/motorizing-standup-desk/): says 2Nm is needed for torque
- [https://github.com/aenniw/ARDUINO/tree/master/skarsta](https://github.com/aenniw/ARDUINO/tree/master/skarsta)
- [https://github.com/flosommerfeld/ESP8266-IKEA-Skarsta-Trotten-Web-Dashboard](https://github.com/flosommerfeld/ESP8266-IKEA-Skarsta-Trotten-Web-Dashboard)
- [https://www.instructables.com/Motorizing-an-IKEA-SKARSTA-Table/](https://www.instructables.com/Motorizing-an-IKEA-SKARSTA-Table/): they use an optional endstop to count how many rotations the motor does, thanks to some flag put on the shaft
- [https://hackcorrelation.blogspot.com/2015/09/ikea-skarsta-sitstanding-desk-hack.html](https://hackcorrelation.blogspot.com/2015/09/ikea-skarsta-sitstanding-desk-hack.html): says 2Nm is needed for torque

