Title: Disabling Voicemail
Date: 2018-01-03 22:00
Category: Miscellaneous
Tags: phone, voicemail
Slug: disabling-voicemail
Authors: Romain Pellerin
Summary: How to disable your voicemail

1) Vous ne répondez pas. (renvoi messagerie si non réponse)
Configuration et activation : **61*(N° long REPONDEUR)**(Nombre/Secondes)# [Bouton vert (Envoi, Appel)]
Désactivation : ##61# [Appel] (Sonne sans arrêt)
Vérification de l'état : *#61# [Appel]
 
2) Vous êtes inaccessible, en dehors d'une couverture réseau cellulaire, ou votre portable est éteint. (renvoi répondeur si non accessible)
Configuration et activation : **62*(N° long REPONDEUR)# [Appel]
Désactivation : ##62# [Appel] (messagerie vocale inaccessible)
Vérification de l'état : *#62# [Appel]
 
3) Vous êtes déjà en ligne. (renvoi répondeur si occupé)
Configuration et activation : **67*(N° long REPONDEUR)# [Appel]
Désactivation : ##67# [Appel] (le numéro sonne occupé)
Vérification de l'état : *#67# [Appel]
 
4) Pour un renvoi systématique vers le répondeur (Voix et Visiophonie) :
Configuration et activation : **21*(N° de REPONDEUR)# [Appel]
Désactivation :  ##21# [Appel]
Vérification de l'état : *#21# [Appel]
 
5) Pour activer le double appel (gérer simultanément une communication en cours et un appel entrant), désactive le renvoi d'appel sur occupé et le remplace par le renvoi d'appel sur non-réponse :
Activation : *43# [Appel]
Désactivation : #43# [Appel]
Vérification : *#43# [Appel]
 
6) Activer tous les renvois conditionnels : *004*(Numéro)#
Vérification de l'état : *#004#
Désactiver tous les renvois conditionnels : #004#

In many scenarios, calls are forwared to your voicemail. You may want to disable it, or re-enable it.

# 1. You are not picking up the phone (no reply)

- Enable forwarding: call &ast;&ast;61&ast;(number to forward to)&ast;&ast;(seconds to wait before forwarding)# - note that your phone will ring as long as your caller is on hold
- Disable it and retain: call #61#
- Disable it and forget: call ##61#
- Reestablish: &ast;61#
- Check status: call &ast;#61#

# 2. You are unreachable (plane mode, phone turned off, no connectivity)

- Enable forwarding: call &ast;&ast;62&ast;(number to forward to)#
- Disable it and retain: call #62#
- Disable it and forget: call ##62#
- Reestablish: &ast;62#
- Check status: call &ast;#62#

# 3. You are already on the phone (busy)

- Enable forwarding: call &ast;&ast;67&ast;(number to forward to)#
- Disable it and retain: call #67#
- Disable it and forget: call ##67#
- Reestablish: &ast;67#
- Check status: &ast;#67#

# 4. All three conditions above

- Enable forwarding: call &ast;&ast;004&ast;(number to forward to)#
- Disable it and retain: call #004#
- Disable it and forget: call ##004#
- Reestablish: &ast;004#
- Check status: &ast;#004#

# 5. You are free, forward all incoming calls

- Enable forwarding: call &ast;&ast;21&ast;(number to forward to)#
- Disable it and retain: call #21#
- Disable it and forget: call ##21#
- Reestablish: &ast;21#
- Check status: &ast;#21#

# 6. All four conditions above

- Enable forwarding: call &ast;&ast;002&ast;(number to forward to)#
- Disable it and retain: call #002#
- Disable it and forget: call ##002#
- Reestablish: &ast;002#
- Check status: &ast;#002#

# 7. Call waiting: make your phone for new incoming phones even if you are alreay on the phone

- Enable call waiting: call &ast;43#
- Disable it: call #43#
- Check status: &ast;#43#

# Complementary information

In France, for mobile network provided 'Free mobile', the voicemail phone number is: 06 95 60 00 11.

[More GSM codes here.](https://community.giffgaff.com/t5/Tips-Guides/Turn-off-voicemail-more-handy-codes/td-p/4542132)

# Tips

To call someone anonymously, type: #31#(the number you are calling). To show your identity, type: &ast;31#(the number you are calling).
