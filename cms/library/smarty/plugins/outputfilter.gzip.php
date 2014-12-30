<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:	outputfilter
 * Name:	gzip
 * Version:	0.1
 * Date:	2003-02-13
 * Author:	Joscha Feth, joscha@feth.com
 * Purpose:	gzip the output, before it is sent to the client
 *          ATTENTION: this filter does only work if caching is disabled,
 *			because the cached page would be gzipped and it seems as Smarty
 *			can not (yet) handle that, even if you send the Content-Encoding yourself.
 *			(However, you need not worry about this, if caching is enabled, this filter just returns the source
 *			without compression.)
 *			Why does it not work with caching (yet)?
 *			----------------------------------------
 *			This is because of Smarty adds some information to the beginning of a cached page in ASCII
 *			format.
 *			Also the file should then be opened in "rb" mode on windows, right now it is (still) opened
 *			in "normal" "r" mode.
 * Install:  Drop into the plugin directory, call 
 *           $smarty->load_filter('output','gzip');
 *           from application.
 * -------------------------------------------------------------
 */
function smarty_outputfilter_gzip($tpl_source, &$smarty)
{
	/*~ the compression level to use
		default: 9
		-------------------------------------
		0		->	9
		less compressed ->	better compressed
		less CPU usage	->	more CPU usage
		-------------------------------------
	*/	
	$compression_level	=	defined('GZIP')&&GZIP?9:0;
	/*~ force compression, even if gzip is not sent in HTTP_ACCEPT_ENCODING,
		for example Norton Internet Security filters this, but 95% percent of
		the browsers do support output compression, including Phoenix and Opera.
		default: yes
	*/
	$force_compession	=	true;
	
	//~ message to append to the template source, if it is compressed
	$append_message = "\n<!-- zlib compression level ".$compression_level." -->";
	
	
	if(	$compression_level &&
	    !headers_sent() && //~ headers are not yet sent
		extension_loaded("zlib") && //~ zlib is loaded
		!$smarty->caching && //~ caching is disabled
                !$smarty->debugging && //~ possible bug IE ?!?
		(strstr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip") || $force_compession)) { //~ correct encoding is sent, or compression is forced			
		$tpl_source = gzencode($tpl_source.$append_message,$compression_level);
		
		header("Content-Encoding: gzip");
		header("Vary: Accept-Encoding");
		header("Content-Length: ".strlen($tpl_source));
				
	}
	
	return $tpl_source;
}
?>