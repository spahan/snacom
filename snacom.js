// open fields around item (x is the vertical position)
function openNeighbours(item) {
    var p = null, l=null, f=null, x = item.getAttribute('x');
    if ( p = item.previousSibling) { p.onclick(); }
    if ( p = item.nextSibling) { p.onclick(); }
    // open above if has a line
    if (l = item.parentNode.previousSibling ) {
        f = l.childNodes[x]; 
        f.onclick();
        if ( p = f.previousSibling) { p.onclick(); }
        if ( p = f.nextSibling) { p.onclick(); }
    }
    // open below if has line.
    if (l = item.parentNode.nextSibling) {
        var f = l.childNodes[x];
        f.onclick();
        if ( p = f.previousSibling) { p.onclick(); }
        if ( p = f.nextSibling) { p.onclick(); }
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
}

