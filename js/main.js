/*
* Checks if clicked link is dev enviroment link
*  if not it displayed a warning
*  */
jQuery( 'a' )
    .click(function() {
        if ( this.href.endsWith("#") || this.href.slice(-2 , -1) === "#" || this.href.slice(-3 , -2) === "#" || this.href == null || this.href == "" ) { }
        else {
            do_the_click( this.href );
            return false;
        }
    });

function do_the_click( url )
{
    var adresse = developePlugin.mainUrl;
    if ( !url.includes(adresse) ) {
        var r = confirm( "Sie verlassen nun die Dev-Umgebung." );
        if (r == true) { window.location = url; }
    }
    else { window.location = url; }
}

/*
* Toggle div display
* */
function toggleDisplay(divClass) {
    var target = document.getElementById(divClass);
    if (target != null) {
        if (target.style.display === 'block') {
            target.style.display = 'none';
        }
        else {
            target.style.display = 'block';
        }
    }
}