<?php global $theme_options; ?>

</div> <!--  end .main-content  -->
</div> <!-- .wrap-inside -->
</div> <!-- .wrapper -->
<?php if ( isset($theme_options['call_to_action_enabled']) && $theme_options['call_to_action_enabled'] == 1 )
		get_template_part( '/includes/quote'); ?>
<div id="footer-wrap-outer" class="primary-color">
	<div id="footer">
		<div id="footer-inside" class="container">
			<?php if(is_active_sidebar('footer-sidebar')) { ?>
				<div id="footer-widgets">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer') ) : ?>
					<?php endif; ?>
				</div> <!-- #footer-widgets  -->
			<?php } ?>
		</div> <!-- #footer-inside  -->
		<div class="clear"></div>
	</div> <!-- #footer  -->
</div> <!-- #footer-wrap-outer  -->

<div id="bottom-info-wrapper">
	<div id="bottom-info" class="container">
		<div id="copyright" ><a href="http://www.herbsmedpharma.com/">Copyright &#64; 2014 Herbs Med Pharma</a></div>
		<?php if ( ! empty( $theme_options['payment_visa'] ) || ! empty( $theme_options['payment_mastercard'] ) || ! empty( $theme_options['payment_amex'] ) || ! empty( $theme_options['payment_paypal'] ) || ! empty( $theme_options['payment_checks'] ) ) { ?>
			<div id="weaccept-wrap">
				<div id="accepted">
					<?php if( ! empty( $theme_options['payment_visa'] )  ) { ?>
						<div class="visa"></div>
					<?php } ?>
					<?php if( ! empty( $theme_options['payment_mastercard'] )  ) { ?>
						<div class="mastercard"></div>
					<?php } ?>
					<?php if( ! empty( $theme_options['payment_amex'] )  ) { ?>
						<div class="amex"></div>
					<?php } ?>
					<?php if( ! empty( $theme_options['payment_paypal'] )  ) { ?>
						<div class="paypal"></div>
					<?php } ?>
					<?php if( ! empty( $theme_options['payment_checks'] )  ) { ?>
						<div class="checks"></div>
					<?php } ?>
				</div> <!-- #accepted -->
			</div> <!-- #weaccept-wrap -->
		<?php } ?>
	</div> <!-- bottom info -->
</div> <!-- #bottom-info-wrapper-->
<?php wp_footer(); ?>
<!-- begin olark code -->
<script data-cfasync="false" type='text/javascript'>/*<![CDATA[*/window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){
f[z]=function(){
(a.s=a.s||[]).push(arguments)};var a=f[z]._={
},q=c.methods.length;while(q--){(function(n){f[z][n]=function(){
f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={
0:+new Date};a.P=function(u){
a.p[u]=new Date-a.p[0]};function s(){
a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){
hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){
return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){
b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{
b.contentWindow[g].open()}catch(w){
c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{
var t=b.contentWindow[g];t.write(p());t.close()}catch(x){
b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({
loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
/* custom configuration goes here (www.olark.com/documentation) */
olark.identify('9816-161-10-9605');/*]]>*/</script><noscript><a href="https://www.olark.com/site/9816-161-10-9605/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="http://www.olark.com?welcome" title="Olark live chat software">Olark live chat software</a></noscript>
<!-- end olark code -->
</body>
</html>