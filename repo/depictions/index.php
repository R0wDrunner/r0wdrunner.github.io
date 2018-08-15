<!DOCTYPE html>
<html lang="en">
<head>
  <title>/supermamon/</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script type="text/javascript" src="js/jquery.querystring.js"></script>
  <script type="text/javascript" src="js/data-loader-engine.js"></script>
  <script type="text/javascript" src="js/ios_version_check.js"></script>
  <style>
	@media (max-width: 767px) {
		body{background:#efeff4!important;margin:0;padding:0;border:0;outline:0;box-sizing:border-box}
	}
	.jumbotron-bg {
		background: green
	}
  </style>
  <script type="text/javascript">

	bundleid = $.QueryString['p'];

    var contentBlocks = 	{
    	"#packageName" :
			{"type":"text","source":"package>name"}
		,"#packageHeader" :
			{"type":"custom"
				,"source":"package>name"
				,"render":function(element,source){
					if (navigator.userAgent.search(/Cydia/) == -1) {
						$(element).show();
					}
				}
			}
		,"#packageVersion" :
			{"type":"text","source":"package>version"}
		,"#packageShortDesc" :
			{"type":"text","source":"package>shortDescription"}
		,"#compatibilityCheck" :
			{"type":"custom"
				,"source":"package>compatibility>firmware"
				,"render":function(element,source){
					var res = ios_version_check(
						$(source).find("miniOS").text(),
						$(source).find("maxiOS").text(),
						$(source).find("otherVersions").text(),
						function(message,isBad) {
							$(element).html(message)
							.addClass( (isBad?'alert-danger':'alert-success'));
						}
					);
					if(res==0) {$(element).hide()}
				}
			}
        ,"#descriptionList"	:
			{"type":"list"
				,"source" :"package>descriptionlist>description"
				,"paragraphElement"	: "<li class='list-group-item'>"
				,"emptyListCallback":function(e){$("#descriptionPanel").hide()}
			}
        ,"#screenshotsLink"	:
			{"type":"custom"
				,"source" :"package>screenshots>screenshot"
				,"render":function(element, source){
					$("#screenshotsLink").remove();
					if ($(source).size() == 0) {
						return
					}
					// create screenshots link
					$("#descriptionList").append(
						$("<a class='link-item list-group-item'>")
							.attr("href","screenshots.html" +bundleid)
							.text("Screenshots")
					);
				}
			}
		,"#versionBadge" : {"type":"text","source":"package>version"}
        ,"#changesList"	:
			{"type":"list"
				,"source" :"package>changelog>change"
				,"reverseRender"    : true
				,"paragraphElement"	: "<li class='list-group-item'>"
				,"emptyListCallback":function(e){$("#changesList").hide()}
			}
		,"#changelogLink" :
            {"type":"custom"
                ,"source" :"package>screenshots>screenshot"
                ,"render":function(element, source){
                    $("#changelogLink").remove();
                    if ($(source).size() == 0) {
                        return
                    }
                    // create screenshots link
                    $("#changesList").append(
                        $("<a class='link-item list-group-item'>")
                            .attr("href","changelog.html?p="+bundleid)
                            .text("Full Changelog")
                    );
                }
            }
		,"#dependencyList" :
			{"type":"list"
				,"source" :"package>dependencies>package"
				,"paragraphElement"	: "<li class='list-group-item'>"
				,"emptyListCallback":function(e){$("#dependenciesContainer").remove()}
			}
		,"#externalLinksList" :
			{"type":"custom"
				,"source" :"package>links>link"
				,"paragraphElement"	: "<li class='list-group-item'>"
				,"render":function(element,source){
					if ($(source).size()==0){
						$('#externalLinksContainer').remove()
					}

                    $.each(source, function(index,data) {
                        var a = $("<a class='link-item list-group-item'>");
                        a.attr("href",$(data).find('url').text());
                        if ($(data).find('iconclass')) {
                            var i =  $("<span>")
                            i.attr("class",$(data).find('iconclass').text());
                            console.log(i);
                            $(a).append(i);
                        }
                        $(a).append($(data).find('name').text());
                        $(element).append(a);
                    }); //each

				}

			}
	}
    $( document ).ready(function() {
        $.ajax({
            type    : "GET",
            dataType: "xml",
            url     : (bundleid+"/info.xml"),
            success : function(xml){
				document.title = $(xml).find("package>name").text();
            	data_loader_engine(contentBlocks,xml)

            },
			cache   : false,
            error: function() {
                $("#packageError").show();
				$("#packageInformation").hide();
            }
        }); //ajax

    }); // ready
  </script>
</head>
<body>
<br />

<div id="packageInformation">

	<div id="packageHeader" class="container" style="display:none">
		<div class="jumbotron">
			<h1 id="packageName"></h1>
			<p id="packageShortDesc">brought to you by Reposi3</p>
		</div>
	</div>

	<div class="container">
	  <div id="compatibilityCheck" class="alert"></div>
	</div>

	<div class="container">
		<h5>DESCRIPTION</h5>
		<ul id="descriptionList" class="list-group">
			<li id="screenshotsLink" />
		</ul>
	</div>

	<div class="container">
		<h5>IN THIS VERSION <span id="versionBadge" class="badge" /></h5>
		<ul id="changesList" class="list-group">
			<li id="changelogLink" />
		</ul>
	</div>
	<div class="container" id="dependenciesContainer">
		<h5>DEPENDENCIES</h5>
		<ul id="dependencyList" class="list-group">
		</ul>
	</div>

	<div class="container" id="externalLinksContainer">
		<h5>LINKS</h5>
		<ul id="externalLinksList" class="list-group">
		</ul>
	</div>

</div>
<div id="packageError" style="display:none">
	<div class="container">
	  <div class="alert alert-danger">
		<strong>Oh snap!</strong> The package you are tying to view is not hosted on this repository.
	  </div>
	</div>
</div>

<script type="text/javascript">
   function setAnchorTargets() {
     // if on Cydia, set link targets to _blank
     if (navigator.userAgent.search(/Cydia/) != -1) {
       $("a").each(function() {
         $(this).attr("target","_blank");
       });
     }
   }

    var repoContents =  {
        "#repoFooterLinks" :
            {"type":"custom"
                ,"source":"repo>footerlinks>link"
                ,"render":function(element,source) {
                    $.each(source, function(index,data) {
                        var a = $("<a class='link-item list-group-item'>");
                        a.attr("href",$(data).find('url').text());
                        if ($(data).find('iconclass')) {
                            var i =  $("<span>")
                            i.attr("class",$(data).find('iconclass').text());
                            console.log(i);
                            $(a).append(i);
                        }
                        $(a).append($(data).find('name').text());
                        $(element).append(a);
                    }); //each
                } //render
            }
    }
    $( document ).ready(function() {
        $.ajax({type: "GET", dataType: "xml",url : ("../repo.xml"),cache: false,
            success : function(xml){
				      data_loader_engine(repoContents,xml);
              setAnchorTargets();
			      },
            error: function() {
              $("#contactInfo").hide();
              setAnchorTargets();
            }
        }); //ajax


    }); // ready

</script>

<div id="contactInfo">
	<div class="container">
		<h5>CONTACT</h5>
		<ul class="list-group" id="repoFooterLinks">
		</ul>
	</div>
</div>


</body>
</html>
