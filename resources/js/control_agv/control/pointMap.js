/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************************************!*\
  !*** ./resources/js/control_agv/control/pointMap.js ***!
  \******************************************************/
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

var PointMap = /*#__PURE__*/function () {
  function PointMap(x, y, z, name, iTextX, iTextY, code, N1, N2, N3, N4, N5, N0, i, xLida, yLida, xNav, yNav, layout) {
    _classCallCheck(this, PointMap);

    this.x = this.x0 = x;
    this.y = this.y0 = y;
    this.z = this.z0 = z;
    this.iTextX = iTextX;
    this.iTextY = iTextY;
    this.name = name;
    this.textX = 2;
    this.code = code; // this.code2  = code2;

    this.N1 = N1;
    this.N2 = N2;
    this.N3 = N3;
    this.N4 = N4;
    this.N5 = N5;
    this.N0 = N0;
    this.i = i;
    this.xLida = xLida;
    this.yLida = yLida;
    this.xNav = xNav;
    this.yNav = yNav;
    this.layout = layout;
    this.mX = this.mY = 0;
    this.check = this.setLine = true;
    this.point = false;
    this.checkLine = false;
    this.value = 'FFFFFF'; // this.x01    = this.y01 = 0;
  }

  _createClass(PointMap, [{
    key: "createMap",
    value: function createMap(ratio, tox, toy) {
      push();
      fill('#' + this.value);
      stroke(51); // ellipse(this.x + tox, this.y + toy, this.z);

      ellipse(this.x * ratio + tox, this.y * ratio + toy, this.z * ratio);
      fill(0);
      textSize(this.textX * ratio * (this.z / 5));
      textAlign(CENTER, CENTER);
      text(this.name, this.x * ratio + tox, this.y * ratio + toy);
      pop();
    }
  }, {
    key: "createPoint",
    value: function createPoint(ratio, tox, toy) {
      push();
      fill(255);
      stroke(51); // point 1

      ellipse(this.x * ratio + tox, (this.y - this.z / 2) * ratio + toy, 2 * ratio * (this.z / 5)); // point 2

      ellipse((this.x + this.z / 2) * ratio + tox, this.y * ratio + toy, 2 * ratio * (this.z / 5)); // ponit 3

      ellipse(this.x * ratio + tox, (this.y + this.z / 2) * ratio + toy, 2 * ratio * (this.z / 5)); // point 4

      ellipse((this.x - this.z / 2) * ratio + tox, this.y * ratio + toy, 2 * ratio * (this.z / 5));
      pop();
    }
  }, {
    key: "colorMap",
    value: function colorMap(val) {
      if (val == '1') {
        this.value = '1DE41D';
      } else {
        this.value = 'FFFFFF';
      }
    }
  }, {
    key: "editColor",
    value: function editColor(col) {
      this.value = col;
    } //// truc X, Y
    // lineX() {
    // 	if (mouseX == (this.x).toFixed(0)) {
    // 	// if (mouseX > ((this.x).toFixed(0)-((this.z)/2).toFixed(0)) && mouseX < ((this.x).toFixed(0)+((this.z)/2).toFixed(0)) || mouseX == (this.x).toFixed(0)) {
    // 		// let x1 = mouseX;
    // 		line(mouseX, mouseY, this.x, this.y);
    // 	}
    // }
    // lineY() {
    // 	if (mouseY == (this.y).toFixed(0)) {
    // 		line(mouseX, mouseY, this.x, this.y);
    // 	}
    // }

  }, {
    key: "moveRect",
    value: function moveRect(ratio, tox, toy) {
      var d = int(dist(this.x * ratio + tox, this.y * ratio + toy, mouseX, mouseY)); // console.log(this.name + '-' + d,(this.z*ratio).toFixed(0), this.x*ratio + tox, this.y*ratio + toy);

      if (d < (this.z / 2 * ratio).toFixed(0)) {
        this.check = false;
      } else {
        this.check = true;
      }
    }
  }, {
    key: "movePoint",
    value: function movePoint(ratio, tox, toy) {
      var d = int(dist(this.x * ratio + tox, this.y * ratio + toy, mouseX, mouseY));

      if (d < (this.z / 2 * ratio + ratio).toFixed(0)) {
        this.point = false;
      } else {
        this.point = true;
      }
    }
  }, {
    key: "statusPoint",
    value: function statusPoint() {
      return this.point;
    }
  }, {
    key: "status",
    value: function status() {
      return this.check;
    }
  }, {
    key: "updatePosition",
    value: function updatePosition(ratio, tox, toy) {
      this.x = Number(((mouseX - tox) / ratio).toFixed(1));
      this.y = Number(((mouseY - toy) / ratio).toFixed(1));
    }
  }, {
    key: "updatePos",
    value: function updatePos(x, y, z) {
      this.x = x;
      this.y = y;
      this.z = z;
    }
  }, {
    key: "returnPosition",
    value: function returnPosition() {
      this.x = this.x0;
      this.y = this.y0;
      this.z = this.z0;
    }
  }, {
    key: "setPosition",
    value: function setPosition() {
      this.x0 = this.x;
      this.y0 = this.y;
      this.z0 = this.z;
    }
  }, {
    key: "showModal",
    value: function showModal(ratio, tox, toy) {
      var d = int(dist(this.x * ratio + tox, this.y * ratio + toy, mouseX, mouseY));

      if (d < this.z / 2 * ratio) {
        $("#errEditAll").hide();
        $("#errEditCode").hide();
        $("#errEditN1").hide();
        $("#errEditN2").hide();
        $("#errEditN3").hide();
        $("#errEditN4").hide();
        $("#errEditX").hide();
        $("#errEditY").hide();
        $("#errEditZ").hide();
        $('#modalEdit').modal();
        $('#positionPoint').text(this.i);
        $('#rectName').val(this.name);
        $('#editLine').val(this.code); // $('#editDescripe').val(this.code2);

        $('#xRect').val(this.x);
        $('#yRect').val(this.y);
        $('#zRect').val(this.z);
        $('#N1Rect').val(this.N1);
        $('#N2Rect').val(this.N2);
        $('#N3Rect').val(this.N3);
        $('#N4Rect').val(this.N4);
        $('#N5Rect').val(this.N5);
        $('#N0Rect').val(this.N0);
        $('#editLayout').val(this.layout);
        $('#editXLida').val(this.xLida);
        $('#editYLida').val(this.yLida);
        $('#editXNav').val(this.xNav);
        $('#editYNav').val(this.yNav);
      }
    } // click vao points

  }, {
    key: "setNewLine",
    value: function setNewLine(ratio, tox, toy) {
      var d = int(dist(this.x * ratio + tox, this.y * ratio + toy, mouseX, mouseY));

      if (d <= this.z / 2 * ratio + ratio && d >= (this.z / 2 * ratio - ratio).toFixed(0)) {
        this.checkLine = true;
        return true;
      }
    } // giu chuot va di chuyen

  }, {
    key: "setNewLine1",
    value: function setNewLine1() {
      this.setLine = true;
    } // reset lai trang thai

  }, {
    key: "resetLine",
    value: function resetLine() {
      this.checkLine = false;
      this.setLine = false;
    }
  }, {
    key: "createNewLine",
    value: function createNewLine(x01, y01, x02, y02, ratio) {
      push();
      fill(255);
      stroke(51);
      strokeWeight(3);
      this.caculate(x01, y01, x02, y02, ratio);
      line(x01, y01, x02, y02);
      line(x02, y02, this.xI1, this.yI1);
      line(x02, y02, this.xH1, this.yH1);
      pop();
    }
  }, {
    key: "caculate",
    value: function caculate(x01, y01, x02, y02, ratio) {
      this.IBN = atan(abs(x01 - x02) / abs(y01 - y02)) - PI / 6;
      this.ZBH = atan(abs(y01 - y02) / abs(x02 - x01)) - PI / 6; // xA < xB + yA >= yB (2) // x1 <= x2 + y1 > y2 (2)

      if (x01 <= x02 && y01 > y02) {
        this.xI1 = -sin(this.IBN) * 2 * ratio + x02;
        this.yI1 = cos(this.IBN) * 2 * ratio + y02;
        this.yH1 = sin(this.ZBH) * 2 * ratio + y02;
        this.xH1 = -cos(this.ZBH) * 2 * ratio + x02;
      } // xA >= xB && yA < yB (4)// xA >= xB && yA < yB (4)


      if (x01 >= x02 && y01 < y02) {
        this.xI1 = sin(this.IBN) * 2 * ratio + x02;
        this.yI1 = -cos(this.IBN) * 2 * ratio + y02;
        this.yH1 = -sin(this.ZBH) * 2 * ratio + y02;
        this.xH1 = cos(this.ZBH) * 2 * ratio + x02;
      } // xA >= xB + yA >= yB (1)// xA > xB + yA >= yB (1)


      if (x01 > x02 && y01 >= y02) {
        this.xI1 = sin(this.IBN) * 2 * ratio + x02;
        this.yI1 = cos(this.IBN) * 2 * ratio + y02;
        this.yH1 = sin(this.ZBH) * 2 * ratio + y02;
        this.xH1 = cos(this.ZBH) * 2 * ratio + x02;
      } // xA < xB + yA < yB (3)// xA < xB + yA <= yB (3)


      if (x01 < x02 && y01 <= y02) {
        this.xI1 = -sin(this.IBN) * 2 * ratio + x02;
        this.yI1 = -cos(this.IBN) * 2 * ratio + y02;
        this.yH1 = -sin(this.ZBH) * 2 * ratio + y02;
        this.xH1 = -cos(this.ZBH) * 2 * ratio + x02;
      }
    }
  }]);

  return PointMap;
}();
/******/ })()
;