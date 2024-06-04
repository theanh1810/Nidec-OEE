/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************************!*\
  !*** ./resources/js/control_agv/control/lineMap.js ***!
  \*****************************************************/
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

var LineMap = /*#__PURE__*/function () {
  function LineMap(x1, y1, x2, y2, x3, y3, x4, y4, name, typ, to, xI1, yI1, xH1, yH1) {
    _classCallCheck(this, LineMap);

    this.x1 = x1;
    this.y1 = y1;
    this.x2 = x2;
    this.y2 = y2;
    this.x3 = x3;
    this.y3 = y3;
    this.x4 = x4;
    this.y4 = y4;
    this.name = name;
    this.typ = typ;
    this.to = to;
    this.xI1 = xI1;
    this.yI1 = yI1;
    this.xH1 = xH1;
    this.yH1 = yH1;
    this.check = false;
    this.point = false;
    this.drawLine = false;
    this.color = '100000';
    this.ZBH = this.IBN = 0;
  }

  _createClass(LineMap, [{
    key: "createLine",
    value: function createLine(ratio, tox, toy) {
      push();
      stroke('#' + this.color);
      strokeWeight(3);
      line(this.x1 * ratio + tox, this.y1 * ratio + toy, this.x2 * ratio + tox, this.y2 * ratio + toy); // |

      line(this.x2 * ratio + tox, this.y2 * ratio + toy, this.x3 * ratio + tox, this.y3 * ratio + toy); // /

      line(this.x2 * ratio + tox, this.y2 * ratio + toy, this.x4 * ratio + tox, this.y4 * ratio + toy); // \

      if (this.xI1 != 0 && this.yI1 != 0 && this.xH1 != 0 && this.yH1 != 0) {
        line(this.x1 * ratio + tox, this.y1 * ratio + toy, this.xI1 * ratio + tox, this.yI1 * ratio + toy); // /

        line(this.x1 * ratio + tox, this.y1 * ratio + toy, this.xH1 * ratio + tox, this.yH1 * ratio + toy); // \
      }

      pop();
    }
  }, {
    key: "createPointLine",
    value: function createPointLine(ratio, tox, toy) {
      push();
      fill(255);
      stroke(51);
      ellipse(this.x1 * ratio + tox, this.y1 * ratio + toy, 1.5 * ratio);
      ellipse(this.x2 * ratio + tox, this.y2 * ratio + toy, 1.5 * ratio);
      pop();
    }
  }, {
    key: "editColor",
    value: function editColor(col) {
      this.color = col;
    }
  }, {
    key: "moveLine",
    value: function moveLine(x1, y1, x2, y2, x3, y3, x4, y4) {
      // console.log(x1, y1, x2, y2, x3, y3, x4, y4);
      if (x1 != '0') {
        this.x1 = x1;
      }

      if (y1 != '0') {
        this.y1 = y1;
      }

      if (x2 != '0') {
        this.x2 = x2;
      }

      if (y2 != '0') {
        this.y2 = y2;
      }

      if (x3 != '0') {
        this.x3 = x3;
      }

      if (y3 != '0') {
        this.y3 = y3;
      }

      if (x4 != '0') {
        this.x4 = x4;
      }

      if (y4 != '0') {
        this.y4 = y4;
      }
    }
  }, {
    key: "clickedLine",
    value: function clickedLine(ratio, tox, toy) {
      var b = int(dist(this.x2 * ratio + tox, this.y2 * ratio + toy, mouseX, mouseY));
      var a = int(dist(this.x1 * ratio + tox, this.y1 * ratio + toy, mouseX, mouseY));
      var m = int(dist(this.x1 * ratio + tox, this.y1 * ratio + toy, this.x2 * ratio + tox, this.y2 * ratio + toy));
      var d = int(sin(acos((b * b + m * m - a * a) / (2 * b * m))) * b);
      var angleB = PI - acos((b * b + m * m - a * a) / (2 * b * m));
      var angleA = PI - acos((a * a + m * m - b * b) / (2 * a * m)); // console.log(abs(d) + '-----' + this.name);

      if (abs(d) <= ratio * 2 && angleB >= PI / 2 && angleA >= PI / 2 || a + b == m && d == 0) {
        this.point = true;
      } else {
        this.point = false;
      }
    }
  }, {
    key: "statusPointLine",
    value: function statusPointLine() {
      return this.point;
    }
  }]);

  return LineMap;
}();
/******/ })()
;