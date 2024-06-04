/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************************************!*\
  !*** ./resources/js/control_agv/control/agvControl.js ***!
  \********************************************************/
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

var layoutMap = $.ajax({
  method: 'get',
  url: window.location.origin + '/control-agv/transport-system/agv-control/layout',
  cache: false,
  async: false,
  // tun off async
  data: {},
  dataType: 'json'
});
var myMap;
var sel;
var myLayout;
var myCanvas;
var pointName;
var mapName;
var setItv;
var pathChang;
var set = false; // Khi move vào map và point

var setLinePoint = false;
var setLinePoint1 = false;
var showModalAny = false;
var ID = 0;
var img, agv1, imgMap, iTextX, iTextY;
var ratio = w = h = 1;
var tox = toy = 0;
var zoom = .01; //zoom step per mouse tick 

var allImg = [];
var rectPoint = [];
var rectMap = [];
var lineMap = [];
var pointList = [];
var lineList = [];
var sumRatio = [];
var data1 = [];
var dataName = [];
var selectLine = [];
var temporary = [];
var agv = [];
var agv0 = [];
var lineMove = [];
var linePointMove = [];
var lineAgv0 = [];
var lineAgv = [];
var newLine = [];
var lineDraw = [];
var colorLine = [];
var lineHide = []; // line with angle != 1-2-3-4

var position = 0;
var x1, x2, y1, y2, x3, y3, x4, y4;
var x01 = y01 = 0;
var x02;
var y02;
var IBN = ZBH = xI1 = yI1 = yH1 = xH1 = 0;
var nameLine;
var dataUpdateLine;
var mX = mY = 0;
var widthImg = width0 = $('#myCanvan').width();
var heightWin = height0 = $(window).height() - 190; // width window - nav

var statusConfig = statusWidth = load_map = false; // Trang thai khi chua config

var l = 0;
var widthOld = $('#my-card').width();
var time = 2000;
var checkTime = 0;
var setColor = ['007bff', '6610f2', 'dc3545', 'fd7e14', 'ffc107', '28a745', '343a40']; // load image

function preload() {
  // load layout map	
  // img layout company
  layoutMap.done(function (data) {
    // console.log(data.data);
    var i = 0;

    for (i = 0; i < data.data.length; i++) {
      var dat = data.data[i];
      allImg[dat.ID] = {
        img: loadImage(dat.Image),
        width: dat.W,
        height: dat.H
      };
    }
  }); // img = loadImage("/dist/img/layout-3.png");
  // img agv
  // agv1 = loadImage("/dist/img/agv1-2.png");
}

function setup() {
  // console.log('run');
  // btn draw map, point, collection map + map, colection point + map
  if ($('#btnConfig').length != 0) {
    var config = select('#btnConfig');
    config.mousePressed(configMap);
  }

  $('#layoutAgv').val($('#layoutAgv  option')[1].value); // btn zoom layout

  var maxi = select('#maximize');
  maxi.mousePressed(resetWidth); // btn select new layout

  sel = select('#layoutAgv');
  sel.changed(loadMap); // load layout persent

  var layout = $('#layoutAgv').val();
  loadMap();
  loadData(layout);
  loadPoint(layout);
  loadImgAgv0(layout);

  if (layout != '0') {
    $('#display .card-header').first().height($('#my-card .card-header').first().height() + (82 - 58)); // console.log('Setup');

    lineMap = [];
    lineHide = [];
    socket.emit('view-map', 0); // socket.emit('view-point', $('#layoutAgv').val());

    socket.emit('layout-agv', $('#layoutAgv').val());
  } // setTimeout(() => {$('#modalLoad').modal('hide');}, 1000);

} // Khi click config


function configMap() {
  statusConfig = !statusConfig;
  imgTheme.resetLine();
  imgTheme.resetMap();
  imgTheme.resetPoint();
  imgTheme.pointStatus = false;
  setIn();

  if (statusConfig) {
    socket.emit('clear-time', $('#layoutAgv').val());
    socket.emit('config-map', {
      user: $('.id-user').text()
    });
  } else {
    socket.emit('layout-agv', $('#layoutAgv').val());
  }
}

socket.on('config-map', function (data) {
  // console.log('config map');
  // console.log(data);
  Toast.fire({
    icon: 'warning',
    text: data
  });
});

function loadMap() {
  // edit link after select new layout
  history.pushState(0, 0, '/control-agv/transport-system/agv-control/select/' + $('#layoutAgv').val()); // if select layout default (val = 0)

  if ($('#layoutAgv').val() == '0') {
    myCanva = createCanvas($('#myCanvas').width, heightWin);
    myCanva.parent('myCanvas');
    load_map = false;
    $('#btnConfig').hide();
    setIn();
  } else {
    // Caculate again width, height
    widthImg = width0 = $('#myCanvan').width();
    heightWin = height0 = $(window).height() - 190; // hide all map + point

    $('.sele').hide(); // show all map + point inside layout

    $('.my-sel' + $('#layoutAgv').val()).show(); // show modal load -> show layout, line, map, point....

    $('#modalLoad').modal(); // allow draw

    load_map = true; // show btn config

    $('#btnConfig').show(); // create new object layout	

    imgMap = new ImgMap(allImg[$('#layoutAgv').val()].img, 1, 1, allImg[$('#layoutAgv').val()].width, allImg[$('#layoutAgv').val()].height); // setup width + height canvan

    myCanva = createCanvas(widthImg - 2, heightWin);
    myCanva.parent('myCanvas'); // create icon draw map, point, line...

    imgTheme = new ImgTheme(Number(widthImg) - 50); // draw map
    // loadData();
    // set tox, toy, ratio

    layout(); // draw img agv
    // loadImgAgv0();
    // draw point
    // loadPoint($('#layoutAgv').val());
    // set time out to draw img agv

    setIn(); // set height display agv

    $('#display').height($('#my-card').height());
    $('#cardMess').height('100%');
  }
} // Set time out to draw img agv -> update new position img agv


function setIn() {
  // clear all setItv
  clearTimeout(setItv);
  setItv = setTimeout(loadImgAgv, time);
} // click phong to


function resetWidth() {
  // set status statusWidth
  statusWidth = !statusWidth; // reset draw collection map + map -> hide icon draw map + map

  imgTheme.resetLine(); // reset draw map -> hide icon draw map

  imgTheme.resetMap(); // reset draw point -> hide icon draw point

  imgTheme.resetPoint(); // reset draw collection map + point -> hide icon draw map + point

  imgTheme.resetLinePoint(); // reset status config -> disable all function doubleClick, clicked...

  statusConfig = false; // if after click btn zoom = true

  if (statusWidth) {
    // hide btn config
    $('#btnConfig').hide(); // hide display agv

    $('#display').hide(); // set width + height canvans

    setTimeout(function () {
      widthImg = $(window).width() - 40;
      heightWin = $(window).height() - 100;
      resizeCanvas(widthImg - 2, heightWin);
      imgTheme.x = Number(widthImg) - 50;
    }, 500);
  } else {
    // show btn config
    $('#btnConfig').show(); // show display agv

    $('#display').show(); // set width + height canvas

    setTimeout(function () {
      widthImg = width0;
      heightWin = height0;
      resizeCanvas(widthImg - 2, heightWin);
      imgTheme.x = Number(widthImg) - 50;
    }, 500);
  }
}

function loadImgAgv() {}

$('#layoutAgv').on('change', function () {
  $('.display-agv').hide(); // console.log('change');

  var layout = $('#layoutAgv').val();

  if (layout != '0') {
    lineMap = [];
    lineHide = [];
    socket.emit('view-map', 0); // loadData(layout);
    // socket.emit('view-point', $('#layoutAgv').val());
    // loadPoint(layout);
    // socket.emit('layout-agv', $('#layoutAgv').val());
    // loadImgAgv0(layout);
  }
}); // create layout + edit new layout after select layout

function loadImgAgv0() {
  // console.log('Call ???');
  // if key down Z + not click btn config
  if (!keyIsDown(90) && !statusConfig) {
    var val = 0; // socket.emit('layout-agv', $('#layoutAgv').val());

    socket.on('layout-agv', function (data) // $.get(window.location.origin + '/agv-control/agv-info', function(data, status)
    {
      // console.log('Layout AGV');
      // console.log(data);
      var clr = 0;
      var dataNew = [];
      var sel = $('#layoutAgv').val();

      for (var i = 0; i < data.data.length; i++) {
        var dat = data.data[i];

        if (sel == dat.Layout) {
          dataNew.push(dat);
        }
      } // setIn();
      // reset all line + color line


      newLine = [];
      colorLine = [];
      l = 0; // array contain(chứa) line agv move of Path

      lineMove = []; // array contain img agv + new position agv

      agv = [];
      lineAgv = [];
      lineDraw = [];
      lineAgv0 = []; // set val

      val = dataNew.length; // hide all display agv

      $('.display-agv').hide();

      for (var _i = 0, _dataNew = dataNew; _i < _dataNew.length; _i++) {
        var _i2 = _dataNew[_i];
        // show all display agv in layout
        $('#card-display' + _i2.AGV_ID).show();
        var val1 = _i2.Battery;
        var bat = (val1 * 100 / 25.5).toFixed(1);
        var color = _i2.Color;
        color = setColor[clr++]; // console.log(bat);

        if (Number(bat) <= '25') {
          $('.bat-ratio-' + _i2.AGV_ID).css('background', 'rgb(255,5,5)');
          $('.bat-ratio-' + _i2.AGV_ID).css('border', '1px solid rgb(255,5,5)');
        } else if (Number(bat) <= '50') {
          $('.bat-ratio-' + _i2.AGV_ID).css('background', 'rgb(255,103,15)');
          $('.bat-ratio-' + _i2.AGV_ID).css('border', '1px solid rgb(255,103,15)');
        } else if (Number(bat) <= '75') {
          $('.bat-ratio-' + _i2.AGV_ID).css('background', 'rgb(145,232,66)');
          $('.bat-ratio-' + _i2.AGV_ID).css('border', '1px solid rgb(145,232,66)');
        } else {
          $('.bat-ratio-' + _i2.AGV_ID).css('background', 'rgba(82,177,82)');
          $('.bat-ratio-' + _i2.AGV_ID).css('border', '1px solid rgba(82,177,82)');

          if (bat < '100') {
            bat = 100;
          }
        }

        $('.bat-ratio-' + _i2.AGV_ID).width(bat);
        $('#bat-' + _i2.AGV_ID).text(bat + '%');
        $('.position-' + _i2.AGV_ID).val(_i2.Position);
        $('.command-' + _i2.AGV_ID).val(_i2.Trans_Id);
        $('.status-' + _i2.AGV_ID).val(_i2.stt_agv);

        if (_i2.MyStatus != '0' && false) {}

        if (load_map) {
          // if agv direction exits
          if (_i2.MyRoute != null && _i2.MyRoute != '0') {
            // cut string after space
            var arr = _i2.MyRoute.toString();

            var j = arr;
            $('#card-display' + _i2.AGV_ID).css('border', '2px solid #' + color); // for (let j of arr) 
            // {
            // cut string after the sign "-"

            var arr1 = j.split(' '); // push color line agv
            // arr1.push(i.Color);

            for (var k = 0; k < arr1.length; k++) {
              if (arr1[k] != '0' && arr1[k + 1] != undefined && arr1[k] != '' && arr1[k + 1] != '') {
                var ar = {
                  name: arr1[k],
                  to: arr1[k + 1],
                  color: color
                };
                lineAgv.push(ar);
                lineDraw.push(arr1[k]);
              }
            } // }

          } // create new agv


          imgAgv = new ImgAgv('', // img agv
          _i2.AgvName, // agv name
          _i2.AGV_ID, // agv id
          Number(_i2.X), // x
          Number(_i2.Y), // y
          25, 25, // w, h
          _i2.Color, // color agv
          angle, // angle agv
          _i2.Trans_Id, // tran now agv
          _i2.Position, // position now agv
          _i2.PositionPnext, // position pnext agv
          _i2.Process, // process agv
          _i2.Battery.toFixed(1), // Battery agv
          _i2.Destination, // Distination agv
          _i2.Code, // Code agv now
          _i2.Direction, // Direction agv now
          _i2.Task, // Task agv now
          _i2.CodePrext, // Code Pnext agv
          _i2.Diff, // Differrence agv
          _i2.Regime, // Regime agv
          _i2.TimePing, // Time Ping agv
          _i2.MyAction, // Action agv
          _i2.MyStatus, // Status agv,
          _i2.MyRoute, // Route agv
          _i2.Avoid, // Avoid agv
          _i2.Distance);
          agv.push(imgAgv);
        }

        val--;
      } // compare agv and agv0


      if (val == 0 && load_map) {
        var lineMapNew = lineMap;
        newLine = [];

        for (var li = 0; li < lineMapNew.length; li++) {
          var fin = lineDraw.indexOf(String(lineMapNew[li].name)); // console.log(fin)

          if (fin != -1 && String(lineAgv[fin].to) == String(lineMapNew[li].to)) {
            // lineMapNew[li].editColor(lineAgv[fin].color);
            newLine.push({
              line: lineMapNew[li],
              color: lineAgv[fin].color
            });
          }
        } // console.log(newLine)


        if (agv0.length == 0 || agv0.length != agv.length) {
          agv0 = agv;
        } else {
          for (var _j = 0; _j < agv0.length; _j++) {
            if (agv[_j].x != agv0[_j].x0 || agv[_j].y != agv0[_j].y0) {
              agv0[_j].run = true;
              agv0[_j].stepX = agv[_j].x;
              agv0[_j].stepY = agv[_j].y;
              agv0[_j].check1 = agv0[_j].check2 = agv0[_j].check3 = agv0[_j].check4 = false;

              agv0[_j].updateNew(agv[_j].x, agv[_j].y, agv[_j].w, agv[_j].h, agv[_j].color, agv[_j].angle, agv[_j].route, agv[_j].direction);
            } else if (agv[_j].angle != agv0[_j].angle || agv[_j].w != agv0[_j].w || agv[_j].h != agv0[_j].h || agv[_j].color != agv0[_j].color) {
              agv0[_j].updateNew(agv[_j].x, agv[_j].y, agv[_j].w, agv[_j].h, agv[_j].color, agv[_j].angle, agv[_j].route, agv[_j].direction);
            } else if (agv[_j].direction != agv0[_j].direction) {
              agv0[_j].updateAngle(agv[_j].direction);
            }
          }
        }
      }

      setTimeout(function () {
        socket.emit('layout-agv', 0);
      }, 3000);
    });
  } else {
    setIn();
  }
} // create point (Hình Vuông)


function loadPoint(layout) {
  // myPoint = $.ajax({
  // 	method  : 'get',
  // 	url     : window.location.origin + '/control-agv/transport-system/agv-control/point-list',
  // 	cache   : false,
  // 	data    : {'layout': layout},
  // 	dataType: 'json'
  // });
  socket.on('view-point', function (data) // myPoint.done(function(data)
  {
    var myLayout = $('#layoutAgv').val(); // console.log('View Point');

    console.log(data);
    pointList = [];

    var _iterator = _createForOfIteratorHelper(data.data),
        _step;

    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var i = _step.value;

        if (i.Layout == myLayout) {
          temporary = [];
          var poi = new MyPoint(i.ID, Number(i.X), Number(i.Y), Number(i.W), Number(i.H), i.NAME, Number(i.MAPNAME), Number(i.ANGLE), Number(i.OFFSET), Number(i.REV), Number(i.AREA), Number(i.PARTITION), Number(i.AGV_TYPE), Number(i.CODE)); ///////////////////////////////////////////////////////////////////////////////////

          pointList.push(poi);

          if (i.ANGLE == '1') // 1
            {
              x1 = Number(i.X);
              y1 = Number(i.Y) + Number(i.Z) / 2;
              x2 = Number(i.X_MAP);
              y2 = Number(i.Y_MAP) - Number(i.Z) / 2; // tâm khác tâm

              if (int(dist(x1, y1, x2, y2)) > Number(i.Z) || true) //int(dist(x1, y1, x2, y2)) > Number(i.Z)
                {
                  calculateAngle(x1, y1, x2, y2);
                  temporary.push(xI1, yI1, xH1, yH1);
                  var lines = new LineMap(x1, y1, x2, y2, temporary[0], temporary[1], temporary[2], temporary[3], i.NAME + 'P', 'N13', i.MAPNAME, xI1, yI1, xH1, yH1);
                  lineMap.push(lines);
                }
            } // xA > xB + yA >= yB (1) // N4


          if (i.ANGLE == '4') // 4
            {
              x1 = Number(i.X) + Number(i.Z) / 2;
              y1 = Number(i.Y);
              x2 = Number(i.X_MAP) - Number(i.Z) / 2;
              y2 = Number(i.Y_MAP);

              if (int(dist(x1, y1, x2, y2)) > Number(i.Z) || true) // int(dist(x1, y1, x2, y2)) > Number(i.Z) 
                {
                  calculateAngle(x1, y1, x2, y2);
                  temporary.push(xI1, yI1, xH1, yH1);
                  calculateAngle(x2, y2, x1, y1);

                  var _lines = new LineMap(x1, y1, x2, y2, temporary[0], temporary[1], temporary[2], temporary[3], i.NAME + 'P', 'N24', i.MAPNAME, xI1, yI1, xH1, yH1);

                  lineMap.push(_lines);
                }
            } // xA < xB + yA <= yB (3) // N3


          if (i.ANGLE == '3') // 3
            {
              x1 = Number(i.X);
              y1 = Number(i.Y) - Number(i.Z) / 2;
              x2 = Number(i.X_MAP);
              y2 = Number(i.Y_MAP) + Number(i.Z) / 2;

              if (int(dist(x1, y1, x2, y2)) > Number(i.Z) || true) // int(dist(x1, y1, x2, y2)) > Number(i.Z)  
                {
                  calculateAngle(x1, y1, x2, y2);
                  temporary.push(xI1, yI1, xH1, yH1);
                  calculateAngle(x2, y2, x1, y1);

                  var _lines2 = new LineMap(x1, y1, x2, y2, temporary[0], temporary[1], temporary[2], temporary[3], i.NAME + 'P', 'N13', i.MAPNAME, xI1, yI1, xH1, yH1);

                  lineMap.push(_lines2);
                }
            } // xA >= xB && yA < yB (4) //N2


          if (i.ANGLE == '2') // 2
            {
              x1 = Number(i.X) - Number(i.Z) / 2;
              y1 = Number(i.Y);
              x2 = Number(i.X_MAP) + Number(i.Z) / 2;
              y2 = Number(i.Y_MAP);

              if (int(dist(x1, y1, x2, y2)) > Number(i.Z) || true) // int(dist(x1, y1, x2, y2)) > Number(i.Z)
                {
                  calculateAngle(x1, y1, x2, y2);
                  temporary.push(xI1, yI1, xH1, yH1);
                  calculateAngle(x2, y2, x1, y1);

                  var _lines3 = new LineMap(x1, y1, x2, y2, temporary[0], temporary[1], temporary[2], temporary[3], i.NAME + 'P', 'N24', i.MAPNAME, xI1, yI1, xH1, yH1);

                  lineMap.push(_lines3);
                }
            } // Angle != 1-2-3-4


          if (i.ANGLE != '1' && i.ANGLE != '2' && i.ANGLE != '3' && i.ANGLE != '4' && i.ANGLE != '0' && false) { var _lines4; }
        }
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }

    setTimeout(function () {
      $('#modalLoad').modal('hide');
    }, 1000);
  }); // .fail(function(err)
  // {
  // 	console.log(err);
  // });
} // set ratio, tox, toy -- trung vơi layoutMap


function layout() {
  myLayout = $.ajax({
    method: 'get',
    url: window.location.origin + '/control-agv/transport-system/agv-control/layout',
    cache: false,
    data: {
      'layout': $('#layoutAgv').val()
    },
    dataType: 'json'
  });
  myLayout.done(function (data) {
    if (data.data.length > 0) {
      var dataNew = data.data[0];
      ID = dataNew.Name;
      ratio = Number(dataNew.Ratio);
      tox = Number(dataNew.X_offset);
      toy = Number(dataNew.Y_offset);
    }
  });
} // draw map after select layout (Hinh Tron)


function loadData() {
  // myMap = $.ajax({
  // 	method  : 'get',
  // 	url     : window.location.origin + '/control-agv/transport-system/agv-control/map',
  // 	cache   :false,
  // 	data    : {'layout': $('#layoutAgv').val()},
  // 	dataType: 'json'
  // });
  // socket.emit('view-map', layout);
  socket.on('view-map', function (data) // myMap.done(function(data) 
  {
    var layout = $('#layoutAgv').val(); // console.log('View Map');
    // console.log(data);
    // reset map, position, line

    rectMap = [];
    position = 0;

    var _iterator2 = _createForOfIteratorHelper(data.data),
        _step2;

    try {
      for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
        var myRect = _step2.value;

        if (myRect.Layout == layout) {
          var x = Number(myRect.X);
          var y = Number(myRect.Y);
          var z = Number(myRect.Z);
          var name = myRect.Name;
          var code = myRect.CODE; // let code2 = myRect.CODE2;

          var N1 = myRect.N1;
          var _N = myRect.N2;
          var _N2 = myRect.N3;
          var _N3 = myRect.N4;
          var N5 = myRect.N5;
          var N0 = myRect.N0;
          var X_LIDA = Number(myRect.X_LIDA);
          var Y_LIDA = Number(myRect.Y_LIDA);
          var X_NAV = Number(myRect.X_NAV);
          var Y_NAV = Number(myRect.Y_NAV);
          var Layout = myRect.Layout; // caculate width text name

          if (name.length == 1) {
            iTextX = 0.65;
            iTextY = 0.8;
          } else if (name.length == 2) {
            iTextX = 1.25;
            iTextY = 0.8;
          } else if (name.length == 3) {
            iTextX = 1.85;
            iTextY = 0.8;
          } else {
            iTextX = 2.3;
            iTextY = 0.8;
          } // create new map


          test = new PointMap(x, y, z, name, iTextX, iTextY, code, N1, _N, _N2, _N3, N5, N0, position, X_LIDA, Y_LIDA, X_NAV, Y_NAV, Layout);
          rectMap.push(test); // x1 <= x2 + y1 > y2 (2) // N1

          if (myRect.N1 != 0) {
            x1 = Number(x);
            y1 = Number(y) - Number(z) / 2;
            x2 = Number(myRect.X_1);
            y2 = Number(myRect.Y_1) + Number(myRect.Z_1) / 2;
            calculateAngle(x1, y1, x2, y2);
            var to = myRect.N1; // craete new line

            myLine = new LineMap(x1, y1, x2, y2, xI1, yI1, xH1, yH1, name, 'N1', to, 0, 0, 0, 0);
            lineMap.push(myLine);
          } // xA > xB + yA >= yB (1) // N4


          if (myRect.N4 != 0) {
            x1 = Number(x) - Number(z) / 2;
            y1 = Number(y);
            x2 = Number(myRect.X_4) + Number(myRect.Z_4) / 2;
            y2 = Number(myRect.Y_4);
            calculateAngle(x1, y1, x2, y2);
            var _to = myRect.N4;
            myLine = new LineMap(x1, y1, x2, y2, xI1, yI1, xH1, yH1, name, 'N4', _to, 0, 0, 0, 0);
            lineMap.push(myLine);
          } // xA < xB + yA <= yB (3) // N3


          if (myRect.N3 != 0) {
            x1 = Number(x);
            y1 = Number(y) + Number(z) / 2;
            x2 = Number(myRect.X_3);
            y2 = Number(myRect.Y_3) - Number(myRect.Z_3) / 2;
            calculateAngle(x1, y1, x2, y2);
            var _to2 = myRect.N3;
            myLine = new LineMap(x1, y1, x2, y2, xI1, yI1, xH1, yH1, name, 'N3', _to2, 0, 0, 0, 0);
            lineMap.push(myLine);
          } // xA >= xB && yA < yB (4) //N2


          if (myRect.N2 != 0) {
            x1 = Number(x) + Number(z) / 2;
            y1 = Number(y);
            x2 = Number(myRect.X_2) - Number(myRect.Z_2) / 2;
            y2 = Number(myRect.Y_2);
            calculateAngle(x1, y1, x2, y2);
            var _to3 = myRect.N2;
            myLine = new LineMap(x1, y1, x2, y2, xI1, yI1, xH1, yH1, name, 'N2', _to3, 0, 0, 0, 0);
            lineMap.push(myLine);
          }
        }
      }
    } catch (err) {
      _iterator2.e(err);
    } finally {
      _iterator2.f();
    }

    setTimeout(function () {
      $('#modalLoad').modal('hide');
    }, 1000);
  });
} // caculate vector (/\)


function calculateAngle(x1, y1, x2, y2) {
  IBN = atan(abs(x1 - x2) / abs(y1 - y2)) - PI / 6;
  ZBH = atan(abs(y1 - y2) / abs(x2 - x1)) - PI / 6; // xA < xB + yA >= yB (2) // x1 <= x2 + y1 > y2 (2)

  if (x1 <= x2 && y1 > y2) {
    xI1 = -sin(IBN) * 2 + x2;
    yI1 = cos(IBN) * 2 + y2;
    yH1 = sin(ZBH) * 2 + y2;
    xH1 = -cos(ZBH) * 2 + x2;
  } // xA >= xB && yA < yB (4)// xA >= xB && yA < yB (4)


  if (x1 >= x2 && y1 < y2) {
    xI1 = sin(IBN) * 2 + x2;
    yI1 = -cos(IBN) * 2 + y2;
    yH1 = -sin(ZBH) * 2 + y2;
    xH1 = cos(ZBH) * 2 + x2;
  } // xA >= xB + yA >= yB (1)// xA > xB + yA >= yB (1)


  if (x1 > x2 && y1 >= y2) {
    xI1 = sin(IBN) * 2 + x2;
    yI1 = cos(IBN) * 2 + y2;
    yH1 = sin(ZBH) * 2 + y2;
    xH1 = cos(ZBH) * 2 + x2;
  } // xA < xB + yA < yB (3)// xA < xB + yA <= yB (3)


  if (x1 < x2 && y1 <= y2) {
    xI1 = -sin(IBN) * 2 + x2;
    yI1 = -cos(IBN) * 2 + y2;
    yH1 = -sin(ZBH) * 2 + y2;
    xH1 = -cos(ZBH) * 2 + x2;
  }
}

function draw() {
  if (load_map) {
    // console.log('run');
    var widthNew = $('#my-card').width();
    var heightNew = $(window).height() - 190;

    if (heightNew != heightWin) {
      widthOld = widthNew;
      heightWin = heightNew;
      loadMap();
      socket.emit('view-map', 0);
    } // write card-body 


    $('#rat').text(__map.ratio + ' : ' + ratio.toFixed(1) + ' - Tox : ' + tox.toFixed(0) + ' - Toy : ' + toy.toFixed(0)); // color background

    background(220); // Draw image map

    imgMap.caculateXY(ratio, tox, toy);
    imgMap.createImg(ratio); // draw new map

    imgTheme.drawElip(ratio);

    if (imgTheme.addPoint) {
      imgTheme.drawRect(ratio);
    } // Check after move or click point map


    if (statusConfig) {
      set = false;

      for (var _i3 = 0, _rectMap = rectMap; _i3 < _rectMap.length; _i3++) {
        var i = _rectMap[_i3];

        if (!i.statusPoint()) {
          set = true;
          break;
        }
      }

      for (var _i4 = 0, _lineMap = lineMap; _i4 < _lineMap.length; _i4++) {
        var _i5 = _lineMap[_i4];

        if (_i5.point) {
          set = true;
          break;
        }
      }
    } // line hide


    for (var _i6 = 0, _lineHide = lineHide; _i6 < _lineHide.length; _i6++) {
      var _i7 = _lineHide[_i6];

      if (statusConfig) {
        _i7.createLine(ratio, tox, toy);

        _i7.editColor('000000');
      } else {
        _i7.createLine(ratio, tox, toy);

        _i7.editColor('C0C0C0');
      }
    } // Draw point


    for (var _i8 = 0, _pointList = pointList; _i8 < _pointList.length; _i8++) {
      var _i9 = _pointList[_i8];

      _i9.createPoint(ratio, tox, toy); // click Q


      if (keyIsDown(77) && _i9.checkClicked && statusConfig) {
        // console.log('ok');
        _i9.newPoint(ratio, tox, toy);
      } else {
        _i9.caculateCor(ratio, tox, toy);
      } // draw tini point


      if (_i9.check && !keyIsDown(77) && !set) {
        _i9.createTiniPoint(ratio, tox, toy);
      } // draw collection map + point


      if (linePointMove.length == 2) {
        _i9.drawLine(ratio, Number(linePointMove[0]), Number(linePointMove[1]), Number(mouseX), Number(mouseY));
      }

      if (linePointMove.length == 4) {
        _i9.drawLine(ratio, Number(linePointMove[0]), Number(linePointMove[1]), Number(linePointMove[2]), Number(linePointMove[3]));
      }
    } // Draw point map


    for (var _i10 = 0; _i10 < rectMap.length; _i10++) {
      if (rectMap[_i10].status()) {
        rectMap[_i10].createMap(ratio, tox, toy);

        rectMap[_i10].colorMap(statusConfig); // set color map


        if (lineMove.indexOf(rectMap[_i10].name) != -1 && !statusConfig) {
          rectMap[_i10].colorMap(!statusConfig);
        }
      } else {
        imgTheme.drawElipNew(ratio, rectMap[_i10].name, rectMap[_i10].iTextX, rectMap[_i10].iTextY, rectMap[_i10].code, _i10, rectMap[_i10].N1, rectMap[_i10].N2, rectMap[_i10].N3, rectMap[_i10].N4, rectMap[_i10].N5, rectMap[_i10].N0, rectMap[_i10].xLida, rectMap[_i10].yLida, rectMap[_i10].xNav, rectMap[_i10].yNav, rectMap[_i10].layout, rectMap[_i10].z);
      } // create new ponit


      if (!rectMap[_i10].statusPoint() && !keyIsDown(77) && statusConfig) {
        rectMap[_i10].createPoint(ratio, tox, toy);
      } // draw colection map + map


      if (statusConfig && imgTheme.lineStatus && !keyIsDown(90) && rectMap[_i10].checkLine && rectMap[_i10].setLine && x02 != undefined && y02 != undefined && !keyIsDown(77)) {
        rectMap[_i10].createNewLine(x01, y01, x02, y02, ratio);
      }
    } // draw line


    for (var _i11 = 0; _i11 < lineMap.length; _i11++) {
      lineMap[_i11].createLine(ratio, tox, toy);

      lineMap[_i11].editColor('C0C0C0');

      if (statusConfig) {
        lineMap[_i11].editColor('000000');
      }

      if (lineMap[_i11].statusPointLine() && !keyIsDown(77)) {
        lineMap[_i11].createPointLine(ratio, tox, toy);
      }
    } // for(let lineNew of lineAgv0)
    // {
    // 	lineNew.createLine(ratio, tox, toy);
    // }
    // draw button icon


    if (statusConfig) {
      imgTheme.buttonCreate();
      imgTheme.buttonCreateLine();
      imgTheme.buttonCreatePoint();
      imgTheme.buttonCreateLinePoint();
      imgTheme.drawTextMap();
      imgTheme.drawTextLineMap();
      imgTheme.drawTextPoint();
      imgTheme.drawTextLinePoint();
    }

    if (!statusConfig) {
      // draw line befor run trafic
      for (var _i12 = 0; _i12 < newLine.length; _i12++) {
        newLine[_i12].line.editColor(newLine[_i12].color);

        newLine[_i12].line.createLine(ratio, tox, toy);
      } // draw agv


      var _iterator3 = _createForOfIteratorHelper(agv0),
          _step3;

      try {
        for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
          var _i13 = _step3.value;

          _i13.drawDirection(ratio, tox, toy);

          _i13.createAgv(ratio, tox, toy);

          _i13.start();
        }
      } catch (err) {
        _iterator3.e(err);
      } finally {
        _iterator3.f();
      }
    }
  } else {
    background(220);
    push();
    fill('#f56954');
    textSize(100);
    text('Select Layout !', $('#myCanvas').width() / 3, heightWin / 2);
    pop();
  }
} // click mouse giong mouseCLicked (Click chuot xuong)


function mousePressed() {
  // console.log(mouseX, mouseY);
  if (load_map) {
    imgTheme.checkMap = true;
    setLinePoint = false;
    setLinePoint1 = false;

    if (statusConfig) {
      // Delete Line and draw new line
      for (var _i14 = 0, _rectMap2 = rectMap; _i14 < _rectMap2.length; _i14++) {
        var map = _rectMap2[_i14];
        map.resetLine();
      } // Phim M de di chuyen 1 diem


      if (keyIsDown(77)) {
        imgTheme.pointStatus = false;

        if (set) {
          for (var _i15 = 0, _rectMap3 = rectMap; _i15 < _rectMap3.length; _i15++) {
            var _map = _rectMap3[_i15];

            _map.moveRect(ratio, tox, toy);
          }

          for (var _i16 = 0, _rectMap4 = rectMap; _i16 < _rectMap4.length; _i16++) {
            var _map2 = _rectMap4[_i16];

            if (!_map2.check) {
              imgTheme.checkMap = false;
            }
          }
        } else {
          for (var _i17 = 0, _pointList2 = pointList; _i17 < _pointList2.length; _i17++) {
            var i = _pointList2[_i17];
            // console.log('run');
            i.clickedOldPoint(ratio);

            if (i.checkClicked) {
              imgTheme.statusPoint();
              imgTheme.lineStatus = false;
              imgTheme.checkMap = false;
            }
          }
        }
      } // click tao line tai diem bat dau x0, y0 (create relationship map and map)


      if (imgTheme.lineStatus) {
        // console.log(dataName);
        data1 = [];

        for (var _i18 = 0, _rectMap5 = rectMap; _i18 < _rectMap5.length; _i18++) {
          var _map3 = _rectMap5[_i18];

          // set checkLine = true
          _map3.setNewLine(ratio, tox, toy);
        }

        for (var _i19 = 0, _rectMap6 = rectMap; _i19 < _rectMap6.length; _i19++) {
          var _map4 = _rectMap6[_i19];

          if (dataName.length == 0 && data1.length == 0 && !keyIsDown(90) && _map4.checkLine) {
            // console.log('run');
            nameLine = '';
            data1.push(mouseX, mouseY);
            x01 = Number(data1[0]);
            y01 = Number(data1[1]); // console.log(data1);
            // console.log(map.name,
            // 			int(dist((x01 - tox)/ratio, (y01 - toy)/ratio, map.x, map.y - map.z/2)),
            // 			int(dist((x01 - tox)/ratio, (y01 - toy)/ratio, map.x + map.z/2, map.y)),
            // 			int(dist((x01 - tox)/ratio, (y01 - toy)/ratio, map.x, map.y + map.z/2)),
            // 			int(dist((x01 - tox)/ratio, (y01 - toy)/ratio, map.x - map.z/2, map.y)) , map.z/5);
            // if (((x01 - tox)/ratio).toFixed(0) >= map.x - 2*(map.z/5) && ((x01 - tox)/ratio).toFixed(0) < (map.x + map.z/2 + 1) && ((y01 - toy)/ratio).toFixed(0) >= (map.y - map.z/2 - 1) && ((y01 - toy)/ratio).toFixed(0) < map.y) 
            // {

            if (int(dist((x01 - tox) / ratio, (y01 - toy) / ratio, _map4.x, _map4.y - _map4.z / 2)) < _map4.z / 5) {
              nameLine = 'N1'; // console.log('N1 :' +int(dist((x01 - tox)/ratio, (y01 - toy)/ratio, map.x, map.y - map.z/2)), map.z/5);
              // } else if (((x01 - tox)/ratio).toFixed(0) > map.x && ((x01 - tox)/ratio).toFixed(0) <= (map.x + map.z/2 + 1) && ((y01 - toy)/ratio).toFixed(0) >= map.y && ((y01 - toy)/ratio).toFixed(0) < (map.y + map.z/2 + 1)) 
              // {							
            } else if (int(dist((x01 - tox) / ratio, (y01 - toy) / ratio, _map4.x + _map4.z / 2, _map4.y)) < _map4.z / 5) {
              nameLine = 'N2'; // console.log('N2 :'+int(dist((x01 - tox)/ratio, (y01 - toy)/ratio, map.x + map.z/2, map.y)) ,map.z/5);
              // } else if (((x01 - tox)/ratio).toFixed(0) > (map.x - map.z/2 - 1) && ((x01 - tox)/ratio).toFixed(0) <= map.x && ((y01 - toy)/ratio).toFixed(0) > map.y && ((y01 - toy)/ratio).toFixed(0) <= (map.y + map.z/2 + 1)) 
              // {							
            } else if (int(dist((x01 - tox) / ratio, (y01 - toy) / ratio, _map4.x, _map4.y + _map4.z / 2)) < _map4.z / 5) {
              nameLine = 'N3'; // console.log('N3 :' +int(dist((x01 - tox)/ratio, (y01 - toy)/ratio, map.x, map.y + map.z/2)) ,map.z/5);
              // }else if (((x01 - tox)/ratio).toFixed(0) >= (map.x - map.z/2 - 1) && ((x01 - tox)/ratio).toFixed(0) < map.x && ((y01 - toy)/ratio).toFixed(0) > (map.y - map.z/2 - 1) && ((y01 - toy)/ratio).toFixed(0) <= map.y) 
              // {							
            } else if (int(dist((x01 - tox) / ratio, (y01 - toy) / ratio, _map4.x - _map4.z / 2, _map4.y)) < _map4.z / 5) {
              nameLine = 'N4'; // console.log('N4 :' +int(dist((x01 - tox)/ratio, (y01 - toy)/ratio, map.x - map.z/2, map.y)) ,map.z/5);
            }

            if (nameLine != '') {
              dataName.push(_map4.name, nameLine); // console.log('data name :' + dataName);
            }
          }
        } // return false;

      } // Add relationship line point (create relationship map and point)


      if (mouseX >= widthImg - 45 && mouseX <= widthImg - 2 && mouseY >= 124 && mouseY <= 168) {
        imgTheme.resetLine();
        imgTheme.resetPoint();
        imgTheme.resetMap();
        imgTheme.statusLinePoint();
      }

      if (imgTheme.linePoint && !keyIsDown(90) && !keyIsDown(77)) {
        linePointMove = [];

        for (var _i20 = 0, _pointList3 = pointList; _i20 < _pointList3.length; _i20++) {
          var _i21 = _pointList3[_i20];

          if (_i21.check && linePointMove.length == 0) {
            setLinePoint1 = true;
            linePointMove.push(_i21.cordX, _i21.cordY);
            pointName = _i21.name;
          }
        }

        for (var _i22 = 0, _rectMap7 = rectMap; _i22 < _rectMap7.length; _i22++) {
          var _i23 = _rectMap7[_i22];

          if (!_i23.point && linePointMove.length == 0) {
            setLinePoint = true;
            linePointMove.push(mouseX, mouseY);
            mapName = _i23.name;
            x02 = Number(linePointMove[0]);
            y02 = Number(linePointMove[1]); // if (((x02 - tox)/ratio).toFixed(0) >= i.x && ((x02 - tox)/ratio).toFixed(0) < (i.x + i.z/2 + 1) && ((y02 - toy)/ratio).toFixed(0) >= (i.y - i.z/2 - 1) && ((y02 - toy)/ratio).toFixed(0) < i.y) 
            // {

            if (int(dist((x02 - tox) / ratio, (y02 - toy) / ratio, _i23.x, _i23.y - _i23.z / 2)) < _i23.z / 5) {
              nameLine = 'N1'; // } else if (((x02 - tox)/ratio).toFixed(0) > i.x && ((x02 - tox)/ratio).toFixed(0) <= (i.x + i.z/2 + 1) && ((y02 - toy)/ratio).toFixed(0) >= i.y && ((y02 - toy)/ratio).toFixed(0) < (i.y + i.z/2 + 1)) 
              // {
            } else if (int(dist((x02 - tox) / ratio, (y02 - toy) / ratio, _i23.x + _i23.z / 2, _i23.y)) < _i23.z / 5) {
              nameLine = 'N2'; // } else if (((x02 - tox)/ratio).toFixed(0) > (i.x - i.z/2 - 1) && ((x02 - tox)/ratio).toFixed(0) <= i.x && ((y02 - toy)/ratio).toFixed(0) > i.y && ((y02 - toy)/ratio).toFixed(0) <= (i.y + i.z/2 + 1)) 
              // {							
            } else if (int(dist((x02 - tox) / ratio, (y02 - toy) / ratio, _i23.x, _i23.y + _i23.z / 2)) < _i23.z / 5) {
              nameLine = 'N3'; // }else if (((x02 - tox)/ratio).toFixed(0) >= (i.x - i.z/2 - 1) && ((x02 - tox)/ratio).toFixed(0) < i.x && ((y02 - toy)/ratio).toFixed(0) > (i.y - i.z/2 - 1) && ((y02 - toy)/ratio).toFixed(0) <= i.y) 
              // {							
            } else if (int(dist((x02 - tox) / ratio, (y02 - toy) / ratio, _i23.x - _i23.z / 2, _i23.y)) < _i23.z / 5) {
              nameLine = 'N4';
            }
          }
        }
      }
    }
  }
} // Nha chuot


function mouseReleased() {
  if (statusConfig && load_map) {
    // Create new relationship map and map
    if (imgTheme.lineStatus && set && !keyIsDown(77)) {
      // console.log(set);
      for (var _i24 = 0, _rectMap8 = rectMap; _i24 < _rectMap8.length; _i24++) {
        var map = _rectMap8[_i24];
        var d = int(dist(map.x * ratio + tox, map.y * ratio + toy, mouseX, mouseY)); // console.log('Name: '+map.name, d, map.z/2 + 2*map.z/5);

        if (!keyIsDown(90) && d <= map.z / 2 + 2 * map.z / 4 && map.setLine) {
          if (dataName.length == 2 && map.name != dataName[0]) {
            // data1.push(mouseX, mouseY);
            // x02 = Number(data1[2]);
            // y02 = Number(data1[3]);
            // if (((x02 - tox)/ratio).toFixed(0) >= map.x && ((x02 - tox)/ratio).toFixed(0) < (map.x + map.z/2 + 1) && ((y02 - toy)/ratio).toFixed(0) >= (map.y - map.z/2 - 1) && ((y02 - toy)/ratio).toFixed(0) < map.y) 
            // {
            // 	nameLine = 'N1';
            // } else if (((x02 - tox)/ratio).toFixed(0) > map.x && ((x02 - tox)/ratio).toFixed(0) <= (map.x + map.z/2 + 1) && ((y02 - toy)/ratio).toFixed(0) >= map.y && ((y02 - toy)/ratio).toFixed(0) < (map.y + map.z/2 + 1)) 
            // {
            // 	nameLine = 'N2';
            // } else if (((x02 - tox)/ratio).toFixed(0) > (map.x - map.z/2 - 1) && ((x02 - tox)/ratio).toFixed(0) <= map.x && ((y02 - toy)/ratio).toFixed(0) > map.y && ((y02 - toy)/ratio).toFixed(0) <= (map.y + map.z/2 + 1)) 
            // {
            // 	nameLine = 'N3';
            // }else if (((x02 - tox)/ratio).toFixed(0) >= (map.x - map.z/2 - 1) && ((x02 - tox)/ratio).toFixed(0) < map.x && ((y02 - toy)/ratio).toFixed(0) > (map.y - map.z/2 - 1) && ((y02 - toy)/ratio).toFixed(0) <= map.y) 
            // {
            // 	nameLine = 'N4';
            // }
            map.setNewLine1();
            dataName.push(map.name);
          }

          if (dataName.length == 3) {
            $('#modalCreateLine').modal({
              backdrop: 'static',
              keyboard: false
            });
            $('#requestLine').text('Nối vị trí ' + dataName[0] + ' hướng ' + dataName[1] + ' tới vị trí ' + dataName[2]);
          }

          map.setNewLine(ratio, tox, toy);
          break;
        }
      }
    } // move point 


    if (imgTheme.pointStatus && keyIsDown(77)) {
      // console.log('ok');
      for (var _i25 = 0, _pointList4 = pointList; _i25 < _pointList4.length; _i25++) {
        var i = _pointList4[_i25];

        if (i.checkClicked) {
          // console.log('set');
          i.setPoint(ratio, tox, toy);
        }

        i.checkClicked = false;
      }
    } // add relationship point and map


    if (imgTheme.linePoint && !keyIsDown(70) && !keyIsDown(90)) {
      if (!setLinePoint1) {
        for (var _i26 = 0, _pointList5 = pointList; _i26 < _pointList5.length; _i26++) {
          var _i27 = _pointList5[_i26];

          if (mouseX >= (_i27.x - _i27.w / 2) * ratio + tox && mouseX <= (_i27.x + _i27.w / 2) * ratio + tox && mouseY >= (_i27.y - _i27.h / 2) * ratio + toy && mouseY <= (_i27.y + _i27.h / 2) * ratio + toy && linePointMove.length == 2) {
            // console.log('point');
            linePointMove.push(_i27.cordX, _i27.cordY);
            $('#modalCreateLinePoint').modal();
            $('#requestLinePoint').text('Bạn Có muốn tạo quan hệ giữa điểm ' + mapName + ' hướng ' + nameLine + ' và ' + _i27.name);
            pointName = _i27.name;
          }
        }
      }

      if (!setLinePoint) {
        for (var _i28 = 0, _rectMap9 = rectMap; _i28 < _rectMap9.length; _i28++) {
          var _i29 = _rectMap9[_i28];

          var _d = int(dist(_i29.x * ratio + tox, _i29.y * ratio + toy, mouseX, mouseY));

          if (!keyIsDown(90) && _d <= _i29.z / 2 * ratio + ratio && _d >= (_i29.z / 2 * ratio - ratio).toFixed(0) && linePointMove.length == 2) {
            // console.log('map');
            linePointMove.push(mouseX, mouseY);
            x02 = Number(linePointMove[2]);
            y02 = Number(linePointMove[3]);

            if (int(dist((x02 - tox) / ratio, (y02 - toy) / ratio, _i29.x, _i29.y - _i29.z / 2)) < _i29.z / 5) {
              nameLine = 'N1'; // } else if (((x02 - tox)/ratio).toFixed(0) > i.x && ((x02 - tox)/ratio).toFixed(0) <= (i.x + i.z/2 + 1) && ((y02 - toy)/ratio).toFixed(0) >= i.y && ((y02 - toy)/ratio).toFixed(0) < (i.y + i.z/2 + 1)) 
              // {
            } else if (int(dist((x02 - tox) / ratio, (y02 - toy) / ratio, _i29.x + _i29.z / 2, _i29.y)) < _i29.z / 5) {
              nameLine = 'N2'; // } else if (((x02 - tox)/ratio).toFixed(0) > (i.x - i.z/2 - 1) && ((x02 - tox)/ratio).toFixed(0) <= i.x && ((y02 - toy)/ratio).toFixed(0) > i.y && ((y02 - toy)/ratio).toFixed(0) <= (i.y + i.z/2 + 1)) 
              // {							
            } else if (int(dist((x02 - tox) / ratio, (y02 - toy) / ratio, _i29.x, _i29.y + _i29.z / 2)) < _i29.z / 5) {
              nameLine = 'N3'; // }else if (((x02 - tox)/ratio).toFixed(0) >= (i.x - i.z/2 - 1) && ((x02 - tox)/ratio).toFixed(0) < i.x && ((y02 - toy)/ratio).toFixed(0) > (i.y - i.z/2 - 1) && ((y02 - toy)/ratio).toFixed(0) <= i.y) 
              // {							
            } else if (int(dist((x02 - tox) / ratio, (y02 - toy) / ratio, _i29.x - _i29.z / 2, _i29.y)) < _i29.z / 5) {
              nameLine = 'N4';
            }

            $('#modalCreateLinePoint').modal();
            $('#requestLinePoint').text('Bạn Có muốn tạo quan hệ giữa điểm ' + _i29.name + ' hướng ' + nameLine + ' và ' + pointName);
            mapName = _i29.name;
          }
        }
      }
    } // Khi nha chuot + khong ve line + khong tao diem + giua phim M -> Move map


    if (keyIsDown(77) && !imgTheme.linePoint && !imgTheme.lineStatus && !imgTheme.pointStatus && !imgTheme.checkMap && !imgTheme.status() && !(mouseX >= 1800 && mouseX <= widthImg - 2 && mouseY >= 0 && mouseY <= 40) && mouseX > 0 && mouseY > 0 && mouseX < 1850 && mouseY < heightWin) {
      // console.log('run');
      for (var _i30 = 0, _rectMap10 = rectMap; _i30 < _rectMap10.length; _i30++) {
        var _map5 = _rectMap10[_i30];
        _map5.check = true;
      }

      $("#errEditAll").hide();
      $("#errEditCode").hide();
      $("#errEditN1").hide();
      $("#errEditN2").hide();
      $("#errEditN3").hide();
      $("#errEditN4").hide();
      $("#errEditX").hide();
      $("#errEditY").hide();
      $("#errEditZ").hide();
      $("#errEditXLida").hide();
      $("#errEditYLida").hide();
      $("#errEditXNav").hide();
      $("#errEdityNav").hide();
      $("#errEditLayout").hide();
      $('#modalEdit').modal({
        backdrop: 'static',
        keyboard: false
      });
      $('#rectName').val(imgTheme.name);
      $('#editLine').val(imgTheme.code); // $('#editDescripe').val(imgTheme.code2);

      $('#xRect').val(((mouseX - tox) / ratio).toFixed(0));
      $('#yRect').val(((mouseY - toy) / ratio).toFixed(0));
      $('#zRect').val(imgTheme.z);
      $('#N1Rect').val(imgTheme.N1);
      $('#N2Rect').val(imgTheme.N2);
      $('#N3Rect').val(imgTheme.N3);
      $('#N4Rect').val(imgTheme.N4);
      $('#N5Rect').val(imgTheme.N5);
      $('#N0Rect').val(imgTheme.N0); // Add Layout, Nav, Lida

      $('#editLayout').val(imgTheme.layout);
      $('#editXLida').val(imgTheme.xLida);
      $('#editYLida').val(imgTheme.yLida);
      $('#editXNav').val(imgTheme.xNav);
      $('#editYNav').val(imgTheme.yNav); // console.log('ok '+imgTheme.layout);

      rectMap[imgTheme.position].updatePosition(ratio, tox, toy);
      rectMap[imgTheme.position].moveRect();
      imgTheme.setCheckMap();
    }
  } // No relationship


  if (dataName.length != 3 || linePointMove.length == 2) {
    dataName = [];
    linePointMove = []; // console.log('reset data name');
  }
}

function keyReleased() {
  if (statusConfig && load_map) {
    for (var _i31 = 0, _rectMap11 = rectMap; _i31 < _rectMap11.length; _i31++) {
      var map = _rectMap11[_i31];
      map.check = true;
    }
  }
} // giu chuot va di chuyen


function mouseDragged() {
  // Khi giu phim 'Z' + chuot trai de di chuyen anh
  if (load_map && keyIsDown(90) && !keyIsDown(77)) //keyIsDown(90) && 
    {
      tox += mouseX - pmouseX;
      toy += mouseY - pmouseY;
    }

  if (statusConfig && load_map) {
    for (var _i32 = 0, _rectMap12 = rectMap; _i32 < _rectMap12.length; _i32++) {
      var setPoint = _rectMap12[_i32];
      setPoint.movePoint(ratio, tox * ratio, toy * ratio);
    } // Khi tạo line


    if (imgTheme.lineStatus) {
      x02 = mouseX;
      y02 = mouseY;

      for (var _i33 = 0, _rectMap13 = rectMap; _i33 < _rectMap13.length; _i33++) {
        var map = _rectMap13[_i33];
        //set all setLine == true
        map.setNewLine1();
      }
    }
  }
} // Lan chuot


function mouseWheel(event) {
  if (load_map) {
    // Khi giu phim 'z' + chuot trai de zoom
    if (mouseX < widthImg - 2 && mouseX > 0 && mouseY > 0 && mouseY < heightWin && keyIsDown(90)) {
      var e = -event.delta;

      if (e > 0) {
        if (ratio < 20) {
          ratio += 0.1;
        }
      } else {
        if (ratio > 0.3) {
          ratio += -0.1;
        }
      }

      return false;
    } else {
      return true;
    }
  }
} // click chuot trai + nhar  chuootj


function mouseClicked() {
  if (statusConfig && load_map) {
    // console.log(mouseX, mouseY);	
    if (mouseX >= widthImg - 45 && mouseX <= widthImg - 2 && mouseY >= 45 && mouseY <= 80) {
      imgTheme.resetMap();
      imgTheme.resetPoint();
      imgTheme.resetLinePoint();
      imgTheme.setLineStatus(); // $('#modalNote').modal();
      // if (imgTheme.lineStatus) 
      // {
      // 	$('#note').text('Bạn đã bật chế độ tạo quan hệ giữa các điểm!');
      // } else 
      // {
      // 	$('#note').text('Bạn đã tắt chế độ tạo quan hệ giữa các điểm!');
      // }
    } // console.log(mouseX, mouseY, pmouseX, pmouseY, ratio, tox, toy);
    // Insert New Map


    if (mouseX >= widthImg - 45 && mouseX <= widthImg - 2 && mouseY >= 0 && mouseY <= 40 && !keyIsDown(90)) {
      imgTheme.resetLine();
      imgTheme.resetPoint();
      imgTheme.resetLinePoint();
      imgTheme.setMapStatus();
    }

    if (imgTheme.status() && !(mouseX >= widthImg - 45 && mouseX <= widthImg - 2 && mouseY >= 0 && mouseY <= 124) && !keyIsDown(90)) {
      var x = ((mouseX - tox) / ratio).toFixed(0);
      var y = ((mouseY - toy) / ratio).toFixed(0);
      var createPoint = $.ajax({
        method: 'get',
        url: window.location.origin + '/control-agv/transport-system/agv-control/create-map',
        cache: false,
        data: {
          'x': x,
          'y': y,
          'layout': $('#layoutAgv').val()
        },
        dataType: 'json'
      });
      createPoint.done(function (data) {
        if (data.status) {
          // console.log(data);
          var iTextX1, iTextY1;

          if (data.data.NAME.length == 1) {
            iTextX1 = 0.65;
            iTextY1 = 0.8;
          } else if (data.data.NAME.length == 2) {
            iTextX1 = 1.25;
            iTextY1 = 0.8;
          } else if (data.data.NAME.length == 3) {
            iTextX1 = 1.85;
            iTextY1 = 0.8;
          } else {
            iTextX1 = 2.3;
            iTextY1 = 0.8;
          }

          test = new PointMap(Number(data.data.X), Number(data.data.Y), Number(data.data.Z), data.data.NAME, iTextX1, iTextY1, 0, 0, 0, 0, 0, 0, 0, rectMap.length - 1, Number(data.data.X_LIDA), Number(data.data.Y_LIDA), Number(data.data.X_NAV), Number(data.data.Y_NAV), Number(data.data.Layout));
          rectMap.push(test);
          $('#mapName').append('<option class=" sele my-sel' + data.data.Layout + ' del' + data.data.NAME + '" value=' + data.data.NAME + '>' + data.data.NAME + '</option>');
        } else {
          Toast.fire({
            icon: 'warning',
            text: data.data
          });
        }
      });
      createPoint.fail(function (err) {
        console.log(err);
      });
    } // Add new point


    if (mouseX >= widthImg - 45 && mouseX <= widthImg - 2 && mouseY >= 84 && mouseY <= 124 && !keyIsDown(90)) {
      imgTheme.resetMap();
      imgTheme.resetLine();
      imgTheme.resetLinePoint();
      imgTheme.statusAddPoint();
    }

    if (imgTheme.addPoint && !(mouseX >= widthImg - 45 && mouseX <= widthImg - 2 && mouseY >= 0 && mouseY <= 124) && !keyIsDown(90)) {
      $('#modalEditLayoutPoint').modal();
      imgTheme.statusAddPoint();
      $('#xPoint').val(((mouseX - tox) / ratio).toFixed(0));
      $('#yPoint').val(((mouseY - toy) / ratio).toFixed(0));
      $('#idPoint').text('0');
      $('#namePoint').val(0);
      $('#mapName').val(0);
      $('#angle').val(0);
      $('#offset').val(0);
      $('#hPoint').val(15);
      $('#wPoint').val(15);
      $('#revPoint').val(0);
      $('#areaPoint').val(0);
      $('#partitionPoint').val(0);
      $('#masterAgvTypePoint').val(0);
      $('#code').val(0);
      $('.select-layout').val($('#layoutAgv').val()); // let addPoint = $.ajax({
      // 	method: 'get',
      // 	url: window.location.origin + '/agv-control/create-point',
      // 	data: {
      // 		'width'  : 15,
      // 		'height' : 15,
      // 		'x'		 : ((mouseX - tox)/ratio).toFixed(0),
      // 		'y'		 : ((mouseY - toy)/ratio).toFixed(0)
      // 	},
      // 	dataType: 'json'
      // });
      // addPoint.done(function(data) 
      // {
      // 	loadData();
      // 	loadPoint($('#layoutAgv').val());
      // }).fail(function(err) 
      // {
      // 	console.log(err);
      // });
    }
  }
} // click edit + show modal, double click chuot trai


function doubleClicked() {
  if (statusConfig && load_map) {
    imgTheme.resetLine();
    imgTheme.resetPoint();
    imgTheme.resetMap();
    imgTheme.resetLinePoint(); // doubleclicked rect

    if (!imgTheme.lineStatus) {
      for (var _i34 = 0, _rectMap14 = rectMap; _i34 < _rectMap14.length; _i34++) {
        var show = _rectMap14[_i34];
        show.showModal(ratio, tox, toy);
      }
    } // doubleclicked line


    selectLine = [];

    for (var _i35 = 0, _lineMap2 = lineMap; _i35 < _lineMap2.length; _i35++) {
      var line = _lineMap2[_i35];

      if (line.statusPointLine()) {
        selectLine.push(line); // $('#modalEditLine').modal();
      }
    }

    for (var _i36 = 0, _pointList6 = pointList; _i36 < _pointList6.length; _i36++) {
      var points = _pointList6[_i36];

      if (!set && points.check) {
        // console.log("ok");
        $("#errAll").hide();
        $("#errNamePoint").hide();
        $("#errMapNamePoint").hide();
        $("#errAngle").hide();
        $("#errOffset").hide();
        $("#errX").hide();
        $("#errY").hide();
        $("#errH").hide();
        $("#errW").hide();
        $("#errRev").hide();
        $("#errArea").hide();
        $("#errPartition").hide();
        $("#errType").hide();
        $("#errCode").hide();
        $('#modalEditLayoutPoint').modal();
        $('#idPoint').text(points.id);
        $('#namePoint').val(points.name);
        $('#mapName').val(points.mapName);
        $('#angle').val(points.angle);
        $('#offset').val(points.offset);
        $('#xPoint').val(points.x);
        $('#yPoint').val(points.y);
        $('#hPoint').val(points.h);
        $('#wPoint').val(points.w);
        $('#revPoint').val(points.rev);
        $('#areaPoint').val(points.area);
        $('#partitionPoint').val(points.partition);
        $('#masterAgvTypePoint').val(points.type);
        $('#code').val(points.code);
        $('.select-layout').val($('#layoutAgv').val());
      }
    } // console.log(selectLine);


    if (selectLine.length != 0) {
      $('#modalEditLine').modal();
      $('#loadingEditLine').hide();
      $('#N1').hide();
      $('#N2').hide();
      $('#N3').hide();
      $('#N4').hide();
      $('#N13').hide();
      $('#N24').hide();
      $('#N1').css({
        "background-color": "white"
      });
      $('#N2').css({
        "background-color": "white"
      });
      $('#N3').css({
        "background-color": "white"
      });
      $('#N4').css({
        "background-color": "white"
      });
      $('#N13').css({
        "background-color": "white"
      });
      $('#N24').css({
        "background-color": "white"
      });
      $('#nameN1').text('');
      $('#nameN2').text('');
      $('#nameN3').text('');
      $('#nameN4').text('');
      $('#nameN13').text('');
      $('#nameN24').text('');

      for (var _i37 = 0, _selectLine = selectLine; _i37 < _selectLine.length; _i37++) {
        var check = _selectLine[_i37];

        if (check.typ == 'N1') {
          $('#N1').show();
          $('#N3').show();
          $('#N1').css({
            "background-color": "yellow"
          });
          $('#nameN1').text(check.name);
          $('#nameN3').text(check.to);
        } else if (check.typ == 'N3') {
          $('#N3').show();
          $('#N1').show();
          $('#N3').css({
            "background-color": "yellow"
          });
          $('#nameN3').text(check.name);
          $('#nameN1').text(check.to);
        }

        if (check.typ == 'N2') {
          $('#N2').show();
          $('#N4').show();
          $('#N2').css({
            "background-color": "yellow"
          });
          $('#nameN2').text(check.name);
          $('#nameN4').text(check.to);
        } else if (check.typ == 'N4') {
          $('#N4').show();
          $('#N2').show();
          $('#N4').css({
            "background-color": "yellow"
          });
          $('#nameN4').text(check.name);
          $('#nameN2').text(check.to);
        }

        if (check.typ == 'N13') {
          $('#N13').show();
          $('#nameN13').text(check.name);
          $('#N13').css({
            "background-color": "yellow"
          });
        }

        if (check.typ == 'N24') {
          $('#N24').show();
          $('#nameN24').text(check.name);
          $('#N24').css({
            "background-color": "yellow"
          });
        }
      }
    } else {
      $('#N1').css({
        "background-color": "white"
      });
      $('#N2').css({
        "background-color": "white"
      });
      $('#N3').css({
        "background-color": "white"
      });
      $('#N4').css({
        "background-color": "white"
      });
    }
  } // return false;

} // move show point angle, di chuyen chout


function mouseMoved() {
  if (statusConfig && load_map) {
    for (var _i38 = 0, _rectMap15 = rectMap; _i38 < _rectMap15.length; _i38++) {
      var setPoint = _rectMap15[_i38];
      setPoint.movePoint(ratio, tox, toy);
    }

    for (var _i39 = 0, _lineMap3 = lineMap; _i39 < _lineMap3.length; _i39++) {
      var line = _lineMap3[_i39];
      line.clickedLine(ratio, tox, toy);
    }

    for (var _i40 = 0, _pointList7 = pointList; _i40 < _pointList7.length; _i40++) {
      var points = _pointList7[_i40];
      points.moveMousePoint(ratio, tox, toy);
    }

    imgTheme.moveMap();
    imgTheme.moveLineMap();
    imgTheme.movePoint();
    imgTheme.moveLinePoint();
  }
}

function updateMap(dataUpdate) {
  var updateMap = $.ajax({
    method: 'get',
    url: window.location.origin + '/control-agv/transport-system/agv-control/update-map',
    cache: false,
    data: dataUpdate,
    dataType: 'json'
  });
  return updateMap;
} // save point + move line


$('#saveEdit').on('click', function () {
  var dataUpdate = {
    '_token': $('meta[name="csrf-token"]').attr('content'),
    'name': $('#rectName').val(),
    'code': $('#editLine').val(),
    'layout': $("#editLayout").val(),
    // 'code2': $('#editDescripe').val(),
    'x': $('#xRect').val(),
    'y': $('#yRect').val(),
    'z': $('#zRect').val(),
    'N1': $('#N1Rect').val(),
    'N2': $('#N2Rect').val(),
    'N3': $('#N3Rect').val(),
    'N4': $('#N4Rect').val(),
    'x_lida': $('#editXLida').val(),
    'y_lida': $('#editYLida').val(),
    'x_nav': $('#editXNav').val(),
    'y_nav': $('#editYNav').val() // 'N5'   : $('#N5Rect').val(),
    // 'N0'   : $('#N0Rect').val(),

  }; // console.log(dataUpdate);

  if (dataUpdate.code == "" || dataUpdate.x == "" || dataUpdate.y == "" || dataUpdate.z == "" || dataUpdate.N1 == "" || dataUpdate.N2 == "" || dataUpdate.N3 == "" || dataUpdate.N4 == "" || dataUpdate.x_lida == "" || dataUpdate.y_lida == "" || dataUpdate.x_nav == "" || dataUpdate.y_nav == "" || dataUpdate.layout == "") {
    $("#errEditAll").show();
    if (dataUpdate.code == "") $("#errEditCode").show();else $("#errEditCode").hide();
    if (dataUpdate.x == "") $("#errEditX").show();else $("#errEditX").hide();
    if (dataUpdate.y == "") $("#errEditY").show();else $("#errEditY").hide();
    if (dataUpdate.z == "") $("#errEditZ").show();else $("#errEditZ").hide();
    if (dataUpdate.N1 == "") $("#errEditN1").show();else $("#errEditN1").hide();
    if (dataUpdate.N2 == "") $("#errEditN2").show();else $("#errEditN2").hide();
    if (dataUpdate.N3 == "") $("#errEditN3").show();else $("#errEditN3").hide();
    if (dataUpdate.N4 == "") $("#errEditN4").show();else $("#errEditN4").hide();
    if (dataUpdate.x_lida == "") $("#errEditXLida").show();else $("#errEditXLida").hide();
    if (dataUpdate.y_lida == "") $("#errEditYLida").show();else $("#errEditYLida").hide();
    if (dataUpdate.x_nav == "") $("#errEditXNav").show();else $("#errEditXNav").hide();
    if (dataUpdate.y_nav == "") $("#errEditYNav").show();else $("#errEditYNav").hide();
    if (dataUpdate.layout == "") $("#errEditLayout").show();else $("#errEditLayout").hide();
  } else {
    $('#loadingEditPoint').show();
    updateMap(dataUpdate).done(function (data) {
      // console.log(data);
      // 		for(let map of rectMap)
      // 		{
      // 			if (map.name == dataUpdate.name) 
      // 			{
      // 		map.x      = dataUpdate.x;
      // 		map.y      = dataUpdate.y;
      // 		map.z      = dataUpdate.z;
      // 		map.x0     = dataUpdate.x;
      // 		map.y0     = dataUpdate.y;
      // 		map.z0     = dataUpdate.z;
      // 		map.N1     = dataUpdate.N1;
      // 		map.N2     = dataUpdate.N2;
      // 		map.N3     = dataUpdate.N3;
      // 		map.N4     = dataUpdate.N4;
      // 		map.N5     = dataUpdate.N5;
      // 		map.N0     = dataUpdate.N0;
      // 		map.X_LIDA = dataUpdate.x_lida;
      // 		map.Y_LIDA = dataUpdate.y_lida;
      // 		map.X_NAV  = dataUpdate.x_nav;
      // 		map.Y_NAV  = dataUpdate.y_nav;
      // 		map.Layout = dataUpdate.layout;
      // 		break;
      // 			}
      // 		}
      // 		for(let i = 0; i < lineMap.length; i++)
      // {
      // 	if (lineMap[i].name == dataUpdate.name)
      // 	{
      // 		lineMap.splice(i, 1);
      // 	}
      // }
      lineMap = [];
      lineHide = [];
      socket.emit('view-map', 1); // socket.emit('view-point', 'deletePoint');

      $('#loadingEditPoint').hide();
      $('#modalEdit').modal('hide');
    }).fail(function (err) {
      $('#loadingEditPoint').hide();
      console.log(err);
    });
  }
}); //close modal

$('#closeEdit').on('click', function () {
  var _iterator4 = _createForOfIteratorHelper(rectMap),
      _step4;

  try {
    for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
      var i = _step4.value;
      i.returnPosition();
    }
  } catch (err) {
    _iterator4.e(err);
  } finally {
    _iterator4.f();
  }
});
$('#btnClose').on('click', function () {
  var _iterator5 = _createForOfIteratorHelper(rectMap),
      _step5;

  try {
    for (_iterator5.s(); !(_step5 = _iterator5.n()).done;) {
      var i = _step5.value;
      i.returnPosition();
    }
  } catch (err) {
    _iterator5.e(err);
  } finally {
    _iterator5.f();
  }
}); //==========delete point======================

$("#delLayoutPoint").on("click", function () {
  $("#modalEditLayoutPoint").modal("hide");
  $("#namePointRequest").text($('#namePoint').val());
  $("#modalRequestDelPoint").modal();
  $('#loadingDeletePoint').hide();
  $('#point').text($('#namePoint').val());
}); //==========function delete point============

$("#deletePoint").on("click", function () {
  // console.log($('#idPoint').text());
  $('#loadingDeletePoint').show();
  $.ajax({
    method: 'get',
    url: window.location.origin + '/control-agv/transport-system/agv-control/delete-point',
    cache: false,
    data: {
      '_token': $('meta[name="csrf-token"]').attr('content'),
      'id': $('#idPoint').text()
    },
    dataType: 'json'
  }).done(function (data) {
    // console.log(data);
    $('#modalRequestDelPoint').modal('hide');
    $('#loadingDeletePoint').hide();
    lineMap = [];
    lineHide = [];
    socket.emit('view-map', 1); // socket.emit('view-point', 'deletePoint');
  }).fail(function (e) {
    console.log(e);
  });
}); // delete point and line

$('#deleteEdit').on('click', function () {
  $('#modalEdit').modal('hide');
  $('#modalRequest').modal();
  $('#loadingDelete').hide();
  $("#map").text($('#rectName').val());
}); // delete point and line

$('#deleteSave').on('click', function () {
  $('#loadingDelete').show();
  var name = $('#rectName').val();
  var delPoint = $.ajax({
    method: 'get',
    url: window.location.origin + '/control-agv/transport-system/agv-control/delete-map',
    cache: false,
    data: {
      'name': name
    },
    dataType: 'json'
  });
  delPoint.done(function (data) {
    // console.log(data);
    $('#modalRequest').modal('hide');
    $('#loadingDelete').hide();
    $('.del' + $('#rectName').val()).remove();
    var lineName = [];

    for (var i = 0; i < rectMap.length; i++) {
      if (rectMap[i].name == name) {
        // console.log(rectMap[i])
        lineName.push(name);
        rectMap.splice(i, 1);
        break;
      }
    }

    for (var _i41 = 0; _i41 < lineMap.length; _i41++) {
      if (lineName.indexOf(lineMap[_i41].name) != -1) {
        lineMap.splice(_i41, 1);
      }
    }

    loadPoint($('#layoutAgv').val());
  });
  delPoint.fail(function (err) {
    console.log(err);
    $('#loadingDelete').hide();
  });
});
$('#N1').on('click', function () {
  if ($('#N1').css("background-color") == 'rgb(255, 255, 0)') {
    $('#N1').css({
      "background-color": "white"
    });
  } else {
    $('#N1').css({
      "background-color": "yellow"
    });
  }
});
$('#N3').on('click', function () {
  if ($('#N3').css("background-color") == 'rgb(255, 255, 0)') {
    $('#N3').css({
      "background-color": "white"
    });
  } else {
    $('#N3').css({
      "background-color": "yellow"
    });
  }
});
$('#N2').on('click', function () {
  if ($('#N2').css("background-color") == 'rgb(255, 255, 0)') {
    $('#N2').css({
      "background-color": "white"
    });
  } else {
    $('#N2').css({
      "background-color": "yellow"
    });
  }
});
$('#N4').on('click', function () {
  if ($('#N4').css("background-color") == 'rgb(255, 255, 0)') {
    $('#N4').css({
      "background-color": "white"
    });
  } else {
    $('#N4').css({
      "background-color": "yellow"
    });
  }
});
$('#N13').on('click', function () {
  if ($('#N13').css("background-color") == 'rgb(255, 255, 0)') {
    $('#N13').css({
      "background-color": "white"
    });
  } else {
    $('#N13').css({
      "background-color": "yellow"
    });
  }
});
$('#N24').on('click', function () {
  if ($('#N24').css("background-color") == 'rgb(255, 255, 0)') {
    $('#N24').css({
      "background-color": "white"
    });
  } else {
    $('#N24').css({
      "background-color": "yellow"
    });
  }
});

function updateLine(dataUpdateLine) {
  $('#loadingEditLine').show();
  var upLine = $.ajax({
    method: 'get',
    url: window.location.origin + '/control-agv/transport-system/agv-control/update-line',
    cache: false,
    data: dataUpdateLine,
    dataType: 'json'
  });
  return upLine;
}

function updateLayoutPoint(dataUpdateLayoutPoint) {
  $('#loadingEditLine').show();
  var upLine = $.ajax({
    method: 'get',
    url: window.location.origin + '/control-agv/transport-system/agv-control/update-layout-point',
    cache: false,
    data: dataUpdateLayoutPoint,
    dataType: 'json'
  });
  return upLine;
}

function updateAllLayoutPoint(dataUpdate) {
  $('#loadingEditLine').show();
  var upLine = $.ajax({
    method: 'get',
    url: window.location.origin + '/control-agv/transport-system/agv-control/update-all-layout-point',
    cache: false,
    data: dataUpdate,
    dataType: 'json'
  });
  return upLine;
}

$('#saveLine').on('click', function () {
  var N1 = N2 = N3 = N4 = 0;
  var name1 = name2 = 0;

  if ($('#N1').css('background-color') == 'rgb(255, 255, 0)') {
    name1 = $('#nameN1').text();
    N1 = $('#nameN3').text();
    var _dataUpdateLine = {
      'name': name1,
      'N1': N1,
      'N2': '',
      'N3': '',
      'N4': ''
    };
    updateLine(_dataUpdateLine).done(function (data) {
      // console.log(data);
      $('#modalEditLine').modal('hide');
      $('#loadingEditLine').hide();
    }).fail(function (err) {
      console.log(err);
    });
  } else if ($('#N1').css('background-color') == 'rgb(255, 255, 255)' && $('#N1').css('display') != 'none') {
    name1 = $('#nameN1').text();
    var _dataUpdateLine2 = {
      'name': name1,
      'N1': 0,
      'N2': '',
      'N3': '',
      'N4': ''
    };
    updateLine(_dataUpdateLine2).done(function (data) {
      // console.log(data);
      $('#modalEditLine').modal('hide');
      $('#loadingEditLine').hide();
    }).fail(function (err) {
      console.log(err);
    });
  }

  if ($('#N3').css('background-color') == 'rgb(255, 255, 0)') {
    name1 = $('#nameN3').text();
    N3 = $('#nameN1').text();
    var _dataUpdateLine3 = {
      'name': name1,
      'N1': '',
      'N2': '',
      'N3': N3,
      'N4': ''
    };
    updateLine(_dataUpdateLine3).done(function (data) {
      // console.log(data);
      $('#modalEditLine').modal('hide');
      $('#loadingEditLine').hide();
    }).fail(function (err) {
      console.log(err);
    });
  } else if ($('#N3').css('background-color') == 'rgb(255, 255, 255)' && $('#N3').css('display') != 'none') {
    name1 = $('#nameN3').text();
    var _dataUpdateLine4 = {
      'name': name1,
      'N1': '',
      'N2': '',
      'N3': 0,
      'N4': ''
    };
    updateLine(_dataUpdateLine4).done(function (data) {
      // console.log(data);
      $('#modalEditLine').modal('hide');
      $('#loadingEditLine').hide();
    }).fail(function (err) {
      console.log(err);
    });
  }

  if ($('#N2').css('background-color') == 'rgb(255, 255, 0)') {
    name1 = $('#nameN2').text();
    N2 = $('#nameN4').text();
    var _dataUpdateLine5 = {
      'name': name1,
      'N1': '',
      'N2': N2,
      'N3': '',
      'N4': ''
    };
    updateLine(_dataUpdateLine5).done(function (data) {
      // console.log(data);
      $('#modalEditLine').modal('hide');
      $('#loadingEditLine').hide();
    }).fail(function (err) {
      console.log(err);
    });
  } else if ($('#N2').css('background-color') == 'rgb(255, 255, 255)' && $('#N2').css('display') != 'none') {
    name1 = $('#nameN2').text();
    var _dataUpdateLine6 = {
      'name': name1,
      'N1': '',
      'N2': 0,
      'N3': '',
      'N4': ''
    };
    updateLine(_dataUpdateLine6).done(function (data) {
      // console.log(data);
      $('#modalEditLine').modal('hide');
      $('#loadingEditLine').hide();
    }).fail(function (err) {
      console.log(err);
    });
  }

  if ($('#N4').css('background-color') == 'rgb(255, 255, 0)') {
    name1 = $('#nameN4').text();
    N4 = $('#nameN2').text();
    var _dataUpdateLine7 = {
      'name': name1,
      'N1': '',
      'N2': '',
      'N3': '',
      'N4': N4
    };
    updateLine(_dataUpdateLine7).done(function (data) {
      // console.log(data);
      $('#modalEditLine').modal('hide');
      $('#loadingEditLine').hide();
    }).fail(function (err) {
      console.log(err);
    });
  } else if ($('#N4').css('background-color') == 'rgb(255, 255, 255)' && $('#N4').css('display') != 'none') {
    name1 = $('#nameN4').text();
    var _dataUpdateLine8 = {
      'name': name1,
      'N1': '',
      'N2': '',
      'N3': '',
      'N4': 0
    };
    updateLine(_dataUpdateLine8).done(function (data) {
      // console.log(data);
      $('#modalEditLine').modal('hide');
      $('#loadingEditLine').hide();
    }).fail(function (err) {
      console.log(err);
    });
  }

  if ($('#N13').css('background-color') == 'rgb(255, 255, 255)' && $('#N13').css('display') != 'none') {
    name1 = $('#nameN13').text();
    var _dataUpdateLine9 = {
      'name': name1
    };
    updateLayoutPoint(_dataUpdateLine9).done(function (data) {
      // console.log(data);
      $('#modalEditLine').modal('hide');
      $('#loadingEditLine').hide();
      loadPoint($('#layoutAgv').val());
    }).fail(function (err) {
      console.log(err);
    });
  }

  if ($('#N24').css('background-color') == 'rgb(255, 255, 255)' && $('#N24').css('display') != 'none') {
    name1 = $('#nameN24').text();
    var _dataUpdateLine10 = {
      'name': name1
    };
    updateLayoutPoint(_dataUpdateLine10).done(function (data) {
      // console.log(data);
      $('#modalEditLine').modal('hide');
      $('#loadingEditLine').hide();
      loadPoint($('#layoutAgv').val());
    }).fail(function (err) {
      console.log(err);
    });
  }

  lineMap = [];
  lineHide = [];
  socket.emit('view-map', 1); // socket.emit('view-point', 'deletePoint');
});

function line_save() {
  $('#loadingInsertLine').show();
  name1 = dataName[0];

  if (dataName[1] == 'N1') {
    dataUpdateLine = {
      'name': name1,
      'N1': dataName[2],
      'N2': '',
      'N3': '',
      'N4': ''
    };
  } else if (dataName[1] == 'N2') {
    dataUpdateLine = {
      'name': name1,
      'N1': '',
      'N2': dataName[2],
      'N3': '',
      'N4': ''
    };
  } else if (dataName[1] == 'N3') {
    dataUpdateLine = {
      'name': name1,
      'N1': '',
      'N2': '',
      'N3': dataName[2],
      'N4': ''
    };
  } else if (dataName[1] == 'N4') {
    dataUpdateLine = {
      'name': name1,
      'N1': '',
      'N2': '',
      'N3': '',
      'N4': dataName[2]
    };
  }

  updateLine(dataUpdateLine).done(function (data) {
    console.log(data);
    $('#modalCreateLine').modal('hide');
    $('#loadingInsertLine').hide();
    lineMap = [];
    lineHide = [];
    socket.emit('view-map', 1); // socket.emit('view-point', 'deletePoint');
  }).fail(function (err) {
    console.log(err);
    $('#loadingInsertLine').hide();
  });
  dataName = [];
  data1 = [];

  var _iterator6 = _createForOfIteratorHelper(rectMap),
      _step6;

  try {
    for (_iterator6.s(); !(_step6 = _iterator6.n()).done;) {
      var map = _step6.value;
      map.resetLine();
    }
  } catch (err) {
    _iterator6.e(err);
  } finally {
    _iterator6.f();
  }
}

$('#lineSave').on('click', function () {
  line_save();
});
$(document).keyup(function (e) {
  if (e.keyCode == 13 && $('#modalCreateLine').css('display') != 'none') {
    line_save();
  }
});
$('#lineCLose').on('click', function () {
  data1 = [];
  dataName = [];
  x01 = y01 = x02 = y02 = 0;

  var _iterator7 = _createForOfIteratorHelper(rectMap),
      _step7;

  try {
    for (_iterator7.s(); !(_step7 = _iterator7.n()).done;) {
      var map = _step7.value;
      map.resetLine();
    }
  } catch (err) {
    _iterator7.e(err);
  } finally {
    _iterator7.f();
  }
}); // update layout point

$('#saveLayoutPoint').on('click', function () {
  var dataUpdate = {
    'id': $('#idPoint').text(),
    'name': $('#namePoint').val(),
    'mapName': $('#mapName').val(),
    'angle': $('#angle').val(),
    'offset': $('#offset').val(),
    'x': $('#xPoint').val(),
    'y': $('#yPoint').val(),
    'h': $('#hPoint').val(),
    'w': $('#wPoint').val(),
    'rev': $('#revPoint').val(),
    'area': $('#areaPoint').val(),
    'partition': $('#partitionPoint').val(),
    'type': $('#masterAgvTypePoint').val(),
    'code': $('#code').val(),
    'layout': $('.select-layout').val()
  };
  console.log(dataUpdate);

  if (dataUpdate.name == '' || dataUpdate.angle == '' || dataUpdate.offset == '' || dataUpdate.x == '' || dataUpdate.y == '' || dataUpdate.h == '' || dataUpdate.w == '' || dataUpdate.rev == '' || dataUpdate.area == '0' || dataUpdate.partition == '0' || dataUpdate.type == 0 || dataUpdate.code == '') {
    $("#errAll").show();
    if (dataUpdate.name == '') $("#errNamePoint").show();else $("#errNamePoint").hide();
    if (dataUpdate.mapName == '0') $("#errMapNamePoint").show();else $("#errMapNamePoint").hide();
    if (dataUpdate.angle == '') $("#errAngle").show();else $("#errAngle").hide();
    if (dataUpdate.offset == '') $("#errOffset").show();else $("#errOffset").hide();
    if (dataUpdate.x == '') $("#errX").show();else $("#errX").hide();
    if (dataUpdate.y == '') $("#errY").show();else $("#errY").hide();
    if (dataUpdate.h == '') $("#errH").show();else $("#errH").hide();
    if (dataUpdate.w == '') $("#errW").show();else $("#errW").hide();
    if (dataUpdate.rev == '') $("#errRev").show();else $("#errRev").hide();
    if (dataUpdate.area == 0) $("#errArea").show();else $("#errArea").hide();
    if (dataUpdate.partition == 0) $("#errPartition").show();else $("#errPartition").hide();
    if (dataUpdate.type == 0) $("#errType").show();else $("#errType").hide();
    if (dataUpdate.code == 0) $("#errCode").show();else $("#errCode").hide();
  } else {
    $('#loadingEditLayoutPoint').show();
    updateAllLayoutPoint(dataUpdate).done(function (data) {
      $('#loadingEditLayoutPoint').hide();
      $('#modalEditLayoutPoint').modal('hide');
      $('.pointLocation' + $('#idPoint').text()).text($('#namePoint').val()); // console.log(data);

      lineMap = [];
      lineHide = [];
      socket.emit('view-map', 1); // socket.emit('view-point', 'deletePoint');
    }).fail(function (err) {
      $('#loadingEditLayoutPoint').hide();
      console.log(err);
    });
  }
}); // close point

$('#btnClosePoint').on('click', function () {
  var _iterator8 = _createForOfIteratorHelper(pointList),
      _step8;

  try {
    for (_iterator8.s(); !(_step8 = _iterator8.n()).done;) {
      var i = _step8.value;
      i.resetPoint();
    }
  } catch (err) {
    _iterator8.e(err);
  } finally {
    _iterator8.f();
  }
}); // save collection line + point

$('#linePointSave').on('click', function () {
  $('#loadingInsertLinePoint').show();
  var updateLinePoint = $.ajax({
    method: 'get',
    url: window.location.origin + '/control-agv/transport-system/agv-control/update-line-point',
    data: {
      'name': pointName,
      'mapName': mapName,
      'angle': nameLine
    },
    dataType: 'json'
  });
  updateLinePoint.done(function (data) {
    lineMap = [];
    lineHide = [];
    socket.emit('view-map', 1); // socket.emit('view-point', 'deletePoint');

    $('#modalCreateLinePoint').modal('hide');
    $('#loadingInsertLinePoint').hide();
  }).fail(function (err) {
    $('#loadingInsertLinePoint').hide();
    console.log(err);
    linePointMove = [];
  });
});
$('#btnClosePoint').on('click', function () {
  var _iterator9 = _createForOfIteratorHelper(pointList),
      _step9;

  try {
    for (_iterator9.s(); !(_step9 = _iterator9.n()).done;) {
      var i = _step9.value;
      i.resetPoint();
    }
  } catch (err) {
    _iterator9.e(err);
  } finally {
    _iterator9.f();
  }
}); // Tim kiem AGV

$('.btn-fill').on('click', function () {
  var id = $(this).attr('id').split('-').pop(); // console.log(id);

  for (var i = 0; i < agv0.length; i++) {
    var myAgv = agv0[i];

    if (id == myAgv.id) {
      tox = -myAgv.x * ratio + $('#my-card').width() / 2;
      toy = -myAgv.y * ratio + $('#my-card').height() / 2;
      break;
    }
  }
}); // Detail AGV

$('.btn-detail').on('click', function () {
  var id = $(this).attr('id').split('-').pop();

  for (var i = 0; i < agv0.length; i++) {
    var myAgv = agv0[i];

    if (id == myAgv.id) {
      $('#modalDetail').modal();
      $('#agvNameDetail').text(myAgv.name);
      $('#positionNow').val(myAgv.position);
      $('#xAgv').val(myAgv.x);
      $('#commandNow').val(myAgv.trans);
      $('#positionPnext').val(myAgv.positionPnext);
      $('#yAgv').val(myAgv.y);
      $('#processNow').val(myAgv.process);
      $('#batteryNow').val(myAgv.battery + 'V');
      $('#angleNow').val(myAgv.angle);
      $('#destinationNow').val(myAgv.destination);
      $('#codeNow').val(myAgv.code);
      $('#directionNow').val(myAgv.direction);
      $('#taskNow').val(myAgv.task);
      $('#codePrext').val(myAgv.codePrext);
      $('#differrenceNow').val(myAgv.diff);
      $('#regimeNow').val(myAgv.regime);
      $('#timePing').val(myAgv.timePing);
      $('#actionNow').val(myAgv.action);
      $('#statusNow').val(myAgv.status);
      $('#routeNow').val(myAgv.route);
      $('#avoidNow').val(myAgv.avoid);
      $('#distanceNow').val(myAgv.distance);
      break;
    }
  }
}); // setInterval(function () {
//     window.location.reload(true);
//     console.log('run');
//   }, 30000)
/******/ })()
;