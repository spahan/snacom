function openNear(item) {
	var p = null;
	item.firstChild.onclick();
	if ( p = item.previousSibling) { p.firstChild.onclick(); }
	if ( p = item.nextSibling) { p.firstChild.onclick(); }
}
// open fields around item (x is the vertical position)
function openNeighbours(item) {
    var p = null, l = null, x = item.getAttribute('x');
    if ( p = item.previousSibling) { p.firstChild.onclick(); }
    if ( p = item.nextSibling) { p.firstChild.onclick(); }
    // open above if has a line
    if (l = item.parentNode.previousSibling ) {
		openNear(l.childNodes[x]);
	} // else is top line. nothing to open
    // open below if has line.
    if (l = item.parentNode.nextSibling) {
		openNear(l.childNodes[x]);
    } else {
		// we dont have a line below. it should be here soon. so delay a little and try again
		setTimeout(function () { if (l=item.parentNode.nextSibling) openNear(l.childNodes[x]); }, 500);
	}
}
// open field using server answer.
function openField(data) {
    if (data.r < 0) document.location = "/loose.php";
    else {
        this.firstChild.src="img/open" + data.r + ".png";
        if (data.n) { 
			$(this.parentNode.parentNode).append(data.n);
			$('#score')[0].innerHTML = Number($('#score')[0].innerHTML) + 1;
		}
        if (data.r == 0) openNeighbours(this, this.getAttribute('x'));
    }
}
function checkField(item) {
    if (item.firstChild.src.match(/open\d\.png/)) return;
    if (item.firstChild.src.match(/flagged\.png/)) return;
    if (item.firstChild.src.match(/opening\.png/)) return;
    item.firstChild.src = "img/opening.png";
    $.ajax('snacom.php', { context: item, data: { x: item.getAttribute('x'), y: item.getAttribute('y') }, success: openField, dataType:'json' });
}

function toggleFlag(item) {
    if (item.firstChild.src.match(/open\d\.png/)) return;
    $.get('snacom.php', { x: item.getAttribute('x'), y: item.getAttribute('y'), f: (item.firstChild.src.match(/flagged\.png/))?0:1});
    item.firstChild.src = (item.firstChild.src.match(/flagged\.png/))? "img/closed.png":"img/flagged.png";
	return false;
}

