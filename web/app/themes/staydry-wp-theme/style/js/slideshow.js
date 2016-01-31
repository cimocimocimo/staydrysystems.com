function preload(sources)
{
  var images = [];
  for (i = 0, length = sources.length; i < length; ++i) {
    images[i] = new Image();
    images[i].src = sources[i];
  }
}

jQuery(document).ready(function(){
    jQuery("a.thumb-link").click(function(event){
        var thumbLinkId = jQuery(this).attr("id");
        var slideId = thumbLinkId.replace('slide_','');
        jQuery("#main-img > img").attr("src", slideUrls[slideId]);
        event.preventDefault();
    });
});
