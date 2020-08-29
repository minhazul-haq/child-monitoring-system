//by rhythm

var index=-1;
		
function preLoadImages()
{
	slideImages = new Array(6);
	
	for(i=0;i<=5;i++)
	{
		slideImages[i] = new Image();
		slideImages[i].src = "/media/slide_images/" + (i+1) + ".jpg";
	}
	
	recur();
}

function recur()
{
	index = (index+1)%6;

	document.getElementById("iHolder").src="/media/slide_images/" + (index+1) + ".jpg";
		
	if (index==0) document.getElementById("iMessage").innerHTML="Monitor your child for 24 hours";
	else if (index==1) document.getElementById("iMessage").innerHTML="You just need an android device!";
	else if (index==2) document.getElementById("iMessage").innerHTML="Monitor from anywhere! Home or office";
	else if (index==3) document.getElementById("iMessage").innerHTML="Lead a tension-free happy life";
	else if (index==4) document.getElementById("iMessage").innerHTML="Don't let your child to be upset";
	else if (index==5) document.getElementById("iMessage").innerHTML="Be a successful parent";
		
	document.getElementById("iHolder").style.opacity = 0.2 ;
		
	window.setTimeout("recurOp(2)",80);
	window.setTimeout("recurOp(3)",180);
	window.setTimeout("recurOp(4)",300);
	window.setTimeout("recurOp(5)",500);
	window.setTimeout("recur()",5000);
}
	
function recurOp(f)
{
	document.getElementById("iHolder").style.opacity = 0.2 * f;
}

function setMapLocation(latitude,longitude,zoom) 
{     
	if (GBrowserIsCompatible()) 
	{
	    var map = new GMap2(document.getElementById("map_canvas"));
        map.setCenter(new GLatLng(latitude,longitude), zoom);
        map.setUIToDefault();
		
		map.addOverlay(new GMarker(new GLatLng(latitude,longitude)));
	}
}