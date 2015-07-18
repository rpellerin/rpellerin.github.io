<?php $title= "Home";
include("header.php");?>
				<section id="slides">
					<img src="../res/images/1.png" alt="Slide" />
					<img src="../res/images/2.png" alt="Slide" />
				</section>
				<section>
					<ul id="home"><li><strong>Speech recognition</strong>: talk and watch the text as it appears on your screen</li><li><strong>Vocal synthesis</strong>: enter a text and your phone pronounces it</li></ul>
				</section>
				<section>
					<h3>What exactly is HandicApp?</h3>
					<p itemprop="description">It’s an application derived from a <strong>project done by first year’s students at 'DUT Informatique'</strong> (Nantes, France), Romain Pellerin, Tom Georgin and Thomas Senez. It has been developed from November 2012 to May 2013. We have elaborated each stage of the development process, whilst keeping in mind the imposed instructions.</p>
					<p>The main goal was to offer deaf people an application through which they can communicate more easily with their contacts. That is done with the help of the voice.</p>
					<p>We would gladly <strong>share our source code</strong> with you if you ask us via <a href="contact" title="Contact">the contact page.</a></p>
                    <h3>After all this work was done</h3>
                    <p>This project resulted in a presentation. You can check out the <a href="../slideshow.html" title="Diaporama">slideshow here.</a></p>
				</section>
				<script src="http://code.jquery.com/jquery-latest.min.js"></script>
				<script src="../res/js/jquery.slides.min.js"></script>
				<script>
					$(function(){
						$("#slides").slidesjs({
							navigation: false,
							play: {
								auto: true,
								interval: 6000,
								effect: "fade",
								pauseOnHover: true,
								restartDelay: 4000
							},
							pagination: {
								effect: "fade",
								active: true
							}
						});
					});
				</script>
<?php include("../res/footer.php");?>