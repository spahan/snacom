function openNear(item) {
    var p = null;
    if ( item.firstChild.src.match(/closed\.png/)) item.firstChild.oncontextmenu();
    if ( p = item.previousSibling ) {
        if ( p.firstChild.src.match(/closed\.png/)) { p.firstChild.oncontextmenu(); }
    }
    if ( p = item.nextSibling ) {
        if ( p.firstChild.src.match(/closed\.png/)) { p.firstChild.oncontextmenu(); }
    }
}
// open fields around item (x is the vertical position)
function openNeighbours(item) {
    var p = null, l = null, x = item.getAttribute('x');
    if ( p = item.previousSibling ) {
        if ( p.firstChild.src.match(/closed\.png/)) { p.firstChild.oncontextmenu(); }
    }
    if ( p = item.nextSibling ) {
        if ( p.firstChild.src.match(/closed\.png/)) { p.firstChild.oncontextmenu(); }
    }
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

function checkNeigbours(item) {
    var p = null, mines_around = parseInt(item.firstChild.src.replace(/\D/g,'')),
        x = item.getAttribute('x'),
        y = item.getAttribute('y'),
        flags_around = 0;
    // check left/right
    if (item.previousSibling && item.previousSibling.firstChild.src.match(/flagged\.png/)) flags_around +=1;
    if (item.nextSibling && item.nextSibling.firstChild.src.match(/flagged\.png/)) flags_around +=1;
    // check above if not first line
    if (item.parentNode.previousSibling) {
        p = item.parentNode.previousSibling.childNodes[x];
        if (p.firstChild.src.match(/flagged\.png/)) flags_around +=1;
        if (p.previousSibling && p.previousSibling.firstChild.src.match(/flagged\.png/)) flags_around +=1;
        if (p.nextSibling && p.nextSibling.firstChild.src.match(/flagged\.png/)) flags_around +=1;
    }
    // check below
    p = item.parentNode.nextSibling.children[x];
    if (p.firstChild.src.match(/flagged\.png/)) flags_around +=1;
    if (p.previousSibling && p.previousSibling.firstChild.src.match(/flagged\.png/)) flags_around +=1;
    if (p.nextSibling && p.nextSibling.firstChild.src.match(/flagged\.png/)) flags_around +=1;

    // open around if mines are done.
    if (parseInt(item.firstChild.src.replace(/\D/g,'')) <= flags_around) {
        openNeighbours(item);
    }
}
function checkField(item) {
    if (item.firstChild.src.match(/flagged\.png/)) return false;
    if (item.firstChild.src.match(/opening\.png/)) return false;
    item.firstChild.src = "img/opening.png";
    $.ajax('snacom.php', { context: item, data: { x: item.getAttribute('x'), y: item.getAttribute('y'), cgid:$('#field')[0].getAttribute('gid') }, success: openField, dataType:'json' });
    return false
}


function toggleFlag(item) {
   if (item.firstChild.src.match(/open\d\.png/)) {
        checkNeigbours(item);
        return false;
    }
    if (item.firstChild.src.match(/open\d\.png/)) return false;
    $.get('snacom.php', { x: item.getAttribute('x'), y: item.getAttribute('y'), f: (item.firstChild.src.match(/flagged\.png/))?0:1, cgid:$('#field')[0].getAttribute('gid')});
    item.firstChild.src = (item.firstChild.src.match(/flagged\.png/))? "img/closed.png":"img/flagged.png";
    return false;
}

