var definition = null;
var full_right = true;
var current_min_scale = 0;
var current_scale = 100;
var flat_line = false;

function X(val) {this.val = val;}
function relX(val) {this.val = val;}
function Y(val) {this.val = val;}
function relY(val) {this.val = val;}

function path() {
    definition = Array.prototype.slice.call(arguments)
    display()
}

function display() {
    svg = document.getElementById('svg');

    width = svg.getBoundingClientRect().width;
    height = svg.getBoundingClientRect().height;
    padding = parseInt(window.getComputedStyle(svg).getPropertyValue('top'));

    line_width = height / 340 * 20;
    min_x = (290 + 53) * height / 340 + padding;
    max_x = width - padding;
    max_y = height - line_width / 2;
    min_y = 100; // probably lower

    if (width < 768) {
        min_y = (min_y + max_y) / 2
    }

    if (!flat_line && !full_right) max_x -= line_width/2

    current_scale = (max_x - min_x) / 100
    if (flat_line) {
        path_def = "H " + max_x
        svg.innerHTML =
            '<path d="m 60 ' + max_y + ' h ' + min_x + ' ' + path_def + '" style="stroke:#fff;stroke-width:' + line_width + 'px;fill:none" />'
    } else if (current_scale >= current_min_scale) {
        path_def = definition.map(function(t) {
                if (t instanceof X)
                    return min_x + t.val/100 * (max_x - min_x)
                else if (t instanceof relX)
                    return t.val/100 * (max_x - min_x)
                else if (t instanceof Y)
                    return min_y + t.val/100 * (max_y - min_y)
                else if (t instanceof relY)
                    return t.val/100 * (max_y - min_y)
                else return t
            }).join(' ')
        svg.innerHTML =
            '<path d="m 60 ' + max_y + ' h ' + min_x + ' ' + path_def + '" style="stroke:#fff;stroke-width:' + line_width + 'px;fill:none" />'
    } else {
        //alert('redo');
        generate()
    }
}

function some_point(v1, v2, target, tolerance) {
    real_min = v1 + (target - tolerance) * (v2 - v1)
    real_max = real_min + 2 * tolerance * (v2 - v1)
    return real_min + Math.random() * (real_max - real_min)
}


function ptrn_valley() {
    if (current_scale < 1.1) return false;

    // goes right zig-zaging, ends at bottom and goes up;
    valley_x = some_point(0, 100, .5, .3)
    valley_y = some_point(0, 100, .75, .2)

    top1_x = some_point(0, valley_x, .5, .3)
    top1_y = some_point(0, valley_y, .5, .3)
    top2_x = some_point(valley_x, 100, .5, .3)
    top2_y = some_point(0, valley_y, .5, .3)

    full_right = false;
    current_min_scale = 1.1;
    path("L", new X(top1_x), new Y(top1_y), new X(valley_x), new Y(valley_y), new X(top2_x), new Y(top2_y), new X(100), new Y(100), "V", new Y(0))
    return true
}

function ptrn_book() {
    if (current_scale < 1.1) return false;

    var local_min_x = 15
    pages = 2 + Math.floor(Math.random() * 5)

    left_angle = Math.PI / 4 + (Math.random() * .9 * Math.PI / 4)
    max_len = 100 / Math.sin(left_angle)
    left_length = some_point(300, max_len, .5, .5)

    right_angle = some_point(.02 * Math.PI, .9 * Math.PI / 8, .5, .5)
    right_length = 500 + 200 * Math.random();

    var pth = ["H", new X(local_min_x)]
    for (var i = 0; i < pages; ++i) {
        angle = left_angle - (left_angle - right_angle) / (pages - 1) * i
        max_len = 100 / Math.sin(angle)
        len = Math.min(
            left_length + (right_length - left_length) / (pages - 1) * i,
            max_len
        )

        if (i)
            pth.push("h", new relX(0.5))
        pth.push(
            "l",
            new relX(Math.cos(angle) * len * 100 / 800),
            new relY(-Math.sin(angle) * len),
            new relX(-Math.cos(angle) * len * 100 / 800),
            new relY(Math.sin(angle) * len)
        )
    }

    current_min_scale = 1.1;
    path.apply(null, pth)
    return true
}


function ptrn_wave() {
    var local_min_x = 15

    if (current_scale < 52 / ((100 - local_min_x) / 4))
        return false

    do {
        forms = 4 + Math.floor(Math.random() * 6)
        form_length = (100 - local_min_x) / forms
        current_min_scale = 52 / form_length;
    } while (current_scale < current_min_scale);

    amplitude = some_point(0, 100, .6, .4)

    var pth = ["H", new X(local_min_x)]
    for (var i = 0; i < forms; ++i) {
        pth.push(
            "v", new relY(-amplitude),
            "h", new relX(form_length / 4),
            "a", new relX(form_length / 4), new relX(form_length / 4), 0, 0, 1, new relX(form_length / 4), new relX(form_length / 4),
            "V", new Y(100),
            "h", new relX(form_length / 2)
        )
    }
    path.apply(null, pth)
    return true;
}

patterns = [
    ptrn_valley,
    ptrn_book,
    ptrn_wave
]

function generate() {
    flat_line = false;
    var start = Math.floor(Math.random() * patterns.length);
    for (var i=0; i < patterns.length; ++i) {
        if (patterns[(start + i) % patterns.length]()) return
    }
    flat_line = true
    display()
}

function header_generator() {
    generate()
    window.onresize = function() {
        if (flat_line) generate()
        else display()
    }
}



$(document).ready(function() {
  $("#menu-toggle").click(function() {
    $("header#main .menu").toggle();
  });
})

