function ivrss_submit()
{
	if(document.ivrss_form.ivrss_path.value=="")
	{
		alert("Please enter the image path.")
		document.ivrss_form.ivrss_path.focus();
		return false;
	}
	else if(document.ivrss_form.ivrss_link.value=="")
	{
		alert("Please enter the target link.")
		document.ivrss_form.ivrss_link.focus();
		return false;
	}
	else if(document.ivrss_form.ivrss_target.value=="")
	{
		alert("Please enter the target status.")
		document.ivrss_form.ivrss_target.focus();
		return false;
	}
	else if(document.ivrss_form.ivrss_title.value=="")
	{
		alert("Please enter the image alt text.")
		document.ivrss_form.ivrss_title.focus();
		return false;
	}
	else if(document.ivrss_form.ivrss_type.value=="")
	{
		alert("Please enter the gallery type.")
		document.ivrss_form.ivrss_type.focus();
		return false;
	}
	else if(document.ivrss_form.ivrss_status.value=="")
	{
		alert("Please select the display status.")
		document.ivrss_form.ivrss_status.focus();
		return false;
	}
	else if(document.ivrss_form.ivrss_order.value=="")
	{
		alert("Please enter the display order, only number.")
		document.ivrss_form.ivrss_order.focus();
		return false;
	}
	else if(isNaN(document.ivrss_form.ivrss_order.value))
	{
		alert("Please enter the display order, only number.")
		document.ivrss_form.ivrss_order.focus();
		return false;
	}
}

function ivrss_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_ivrss_display.action="options-general.php?page=image-vertical-reel-scroll-slideshow&ac=del&did="+id;
		document.frm_ivrss_display.submit();
	}
}	

function ivrss_redirect()
{
	window.location = "options-general.php?page=image-vertical-reel-scroll-slideshow";
}

function ivrss_help()
{
	window.open("http://www.gopiplus.com/work/2011/05/30/wordpress-plugin-image-vertical-reel-scroll-slideshow/");
}