/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/lang/index.js":
/*!************************************!*\
  !*** ./resources/js/lang/index.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var lang = document.querySelector('html').getAttribute('lang');
var translations = {};
var requireModules = __webpack_require__("./resources/js/lang/translations sync \\.js$");
requireModules.keys().forEach(function (modulePath) {
  var key = modulePath.replace(/(^.\/)|(.js$)/g, '');
  translations[key] = requireModules(modulePath)["default"];
});
var t = function t(text) {
  return translations[lang][text] || text;
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (t);

/***/ }),

/***/ "./resources/js/lang/translations/en.js":
/*!**********************************************!*\
  !*** ./resources/js/lang/translations/en.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _vi__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./vi */ "./resources/js/lang/translations/vi.js");

var en = {};
Object.keys(_vi__WEBPACK_IMPORTED_MODULE_0__["default"]).forEach(function (key) {
  en[key] = key;
});
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (en);

/***/ }),

/***/ "./resources/js/lang/translations/ko.js":
/*!**********************************************!*\
  !*** ./resources/js/lang/translations/ko.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({});

/***/ }),

/***/ "./resources/js/lang/translations/vi.js":
/*!**********************************************!*\
  !*** ./resources/js/lang/translations/vi.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var _NumberOfRecords_M;
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_NumberOfRecords_M = {
  "Number of records _MENU_": "Số lượng bản ghi _MENU_",
  "Showing _START_ to _END_ of _TOTAL_ entries": "Bản ghi từ _START_ đến _END_ của _TOTAL_ bản ghi",
  "Name": "Tên",
  "Symbols": "Mã",
  "Type": "Loại",
  "Note": "Ghi chú",
  "User Created": "Người tạo",
  "Time Created": "Thời gian tạo",
  "User Updated": "Người cập nhật",
  "Time Updated": "Thời gian cập nhật",
  "Action": "Hành Động",
  "Edit": "Sửa",
  "Delete": "Xóa",
  "Start Time": "Thời Gian Bắt Đầu",
  "End Time": "Thời Gian Kết Thúc",
  "Cycle Time": "Chu Kì Sản Xuất 1 Sản Phẩm ",
  "Detail": "Chi Tiết",
  "Month": "Tháng",
  "Year": "Năm",
  "Location Take Materials": "Vị Trí Lấy Hàng",
  "Materials Return Location": "Vị Trí Trả Hàng",
  "Destroy": "Hủy"
}, _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_NumberOfRecords_M, "Detail", "Chi Tiết"), "Success", "Hoàn Thành"), "Product", "Sản Phẩm"), "Machine", "Máy Sản Xuất"), "Dont Production", "Chưa Sản Xuất"), "Are Production", "Đang Sản Xuất"), "Success Production", "Hoàn Thành Sản Xuất"), "Status", "Trạng Thái"), "Export", "Xuất Kho"), "Materials", "Nguyên Vật Liệu"), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_NumberOfRecords_M, "Dont Export", "Chưa Xuất"), "Are Export", "Đang Xuất"), "Success Export", "Hoàn Thành Xuất"), "Stock Min", "Tồn Giới Hạn"), "Cavity", "Cavity"), "Mold", "Khuôn"), "Quantity Mold", "Số Lượng Khuôn"), "Quantity", "Số Lượng"), "Date", "Ngày"), "Unit", "Đơn Vị Tính"), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_NumberOfRecords_M, "Parking", "Đơn Vị Đóng Gói"), "History", "Lịch Sử"), "Return", "Khôi Phục"), "Update", "Cập Nhật"), "Delete", "Xóa"), "Processing", "Đang Thực Hiện"), "Enable", "Kích Hoạt"), "Disable", "Vô Hiệu Hóa"), "Part", "Bộ Phận"), "Time Start", "Thời Gian Bắt Đầu"), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_NumberOfRecords_M, "Time End", "Thời Gian Kết Thúc"), "Manager AGV", "Người Quản Lý AGV"), "Maintenance Time", "Thời Gian Bảo Trì"), "Maintenance Date", "Ngày Bảo Dưỡng"), "Warehouse", "Kho"), "Plan", "Kế Hoạch"), "Production", "Sản Xuất"), "Output", "Sản Lượng"), "End", "Kết Thúc"), "Error", "Lỗi"), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_NumberOfRecords_M, "Time Real Start", "Bắt Đầu Thực Tế"), "Time Real End", "Kết Thúc Thực Tế"), "Symbols Plan", "Mã Chỉ Thị"), "Infor", "Thông Tin"), "Normal", "Xuất Thường"), "Cancel", "Hủy"), "Confirm", "Xác nhận"), "Close", "Đóng"), "Start", "Bắt Đầu"), "User Name", "Tên Đăng Nhập"), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_NumberOfRecords_M, "ENABLE", "Kích Hoạt"), "DISABLE", "Không Kích Hoạt"), "Action Name", "Kiểu Hành Động"), "INSERT", "Thêm Mới"), "Insert", "Thêm Mới"), "OEE", "Hiệu Suất Tổng Thể"), "Availability", "Khả Dụng"), "Performance", "Hiệu Suất"), "Quality", "Chất Lượng"), "shift", "ca"), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_NumberOfRecords_M, "Shift", "ca"), "day", "ngày"), "Description", "Chú Thích"), "Find AGV", "Tìm AGV"), "Role", "Vai Trò"), "Waiting for AGV", "Chờ AGV"), "AGV Shipping", "AGV Đang Chuyển Hàng"), "IsDelete", "Xóa"), "To", "Tới"), "Command", "Lệnh"), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_NumberOfRecords_M, "INSERT_USER", "Thêm Mới Tài Khoản"), "INSERT_ROLE", "Thêm Mới Vai Trò"), "Update_User", "Cập Nhật Tài Khoản"), "Delete_Role", "Xóa Vai Trò"), "Command AGV Was Destroy", "Lệnh AGV đã bị hủy"), "Select machine", "Chọn máy sản xuất"), "Loading data", "Đang tải dữ liệu"), "Mold code", "Mã khuôn"), "Actual start time", "Bắt đầu thực tế"), "Cycle time", "Thời gian đóng mở khuôn"), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_NumberOfRecords_M, "Quantity NG", "Số lượng sản phẩm lỗi"), "Quantity NG of", "Số lượng sản phẩm lỗi của"), "OEE parameter chart", "Biểu đồ thông số hiệu suất OEE"), "Active timeline chart", "Biểu đồ thời gian hoạt động"), "minute", "phút"), "minutes", "phút"), "hour", "giờ"), "Machine stop logs", "Dừng máy"), "Defective products", "Sản phẩm lỗi"), "Statistical chart of OEE by machine", "Biểu đồ thống kê hiệu suất theo máy"), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_NumberOfRecords_M, "Statistical chart of OEE by day", "Biểu đồ thống kê hiệu suất theo ngày"), "View", "Xem"), "Rows per page", "Số lượng bản ghi"), "Error stop and no-error stop rate chart", "Biểu đồ tỷ lệ dừng lỗi và không lỗi"), "Machine error rate chart", "Biểu đồ tỷ lệ lỗi máy sản xuất"), "No-error stop rate chart", "Biểu đồ tỷ lệ dừng không lỗi"), "Machine stop rate chart due to quality", "Biểu đồ tỷ lệ dừng máy cho chất lượng"), "Statistics chart of machine time stop", "Biểu đồ thống kê thời gian dừng máy"), "Statistics chart of defective product", "Biểu đồ thống kê sản phẩm lỗi"), "Stop Time", "Thời Gian Dừng"), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_NumberOfRecords_M, "Error Code", "Mã Lỗi"), "Error Type", "Loại Lỗi"), "Quantity Error", "Số lượng lỗi"), "Iot disconnect", "Mất kết nối Iot"), "All machine", "Tất cả máy sản xuất"), "Quantity produced", "Số lượng đã sản xuất"), "Quantity produced of", "Số lượng đã sản xuất của"), "run", "Chạy"), "stopError", "Dừng do lỗi"), "stopNotError", "Dừng không lỗi"), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_NumberOfRecords_M, "stop due to error", "Dừng do lỗi"), "stop not error", "Dừng không lỗi"), "stop due to quality", "Dừng do chất lượng"), "machine stop", "Dừng máy"), "products", "sản phẩm"), "Export excel", "Xuất excel"), "Duration", "Thời lượng"), "Total stop time", "Tổng thời gian dừng"), "Line", "Line sản xuất"), "Monitor", "Giám sát máy sản xuất"), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_NumberOfRecords_M, "Monitor Line", "Giám sát máy sản xuất"), "Data analysis", "Phân tích dữ liệu"), "Check all", "Chọn tất cả"), "Submit", "Nhập"), "Disconnected", "Máy tắt"), "RUNNING", "Máy chạy"), "ERROR", "Máy lỗi"), "STOP", "Máy dừng"));

/***/ }),

/***/ "./resources/js/lang/translations sync \\.js$":
/*!*****************************************************************!*\
  !*** ./resources/js/lang/translations/ sync nonrecursive \.js$ ***!
  \*****************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var map = {
	"./en.js": "./resources/js/lang/translations/en.js",
	"./ko.js": "./resources/js/lang/translations/ko.js",
	"./vi.js": "./resources/js/lang/translations/vi.js"
};


function webpackContext(req) {
	var id = webpackContextResolve(req);
	return __webpack_require__(id);
}
function webpackContextResolve(req) {
	if(!__webpack_require__.o(map, req)) {
		var e = new Error("Cannot find module '" + req + "'");
		e.code = 'MODULE_NOT_FOUND';
		throw e;
	}
	return map[req];
}
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = "./resources/js/lang/translations sync \\.js$";

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!**************************************!*\
  !*** ./resources/js/account/user.js ***!
  \**************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _lang__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lang */ "./resources/js/lang/index.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
$('.select2').select2();

var route = "".concat(window.location.origin, "/api/settings/account");
var route_show = "".concat(window.location.origin, "/account/user/show");
var route_his = "".concat(window.location.origin, "/api/settings/account/history");
console.log(route);
var table = $('#tableUser').DataTable({
  scrollX: true,
  aaSorting: [],
  language: {
    lengthMenu: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Number of records _MENU_'),
    info: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Showing _START_ to _END_ of _TOTAL_ entries'),
    paginate: {
      previous: '‹',
      next: '›'
    }
  },
  processing: true,
  dom: 'rt<"bottom"flp><"clear">',
  serverSide: true,
  ordering: false,
  searching: false,
  lengthMenu: [10, 15, 20, 25, 50],
  ajax: {
    url: route,
    dataSrc: 'data',
    data: function data(d) {
      delete d.columns;
      delete d.order;
      delete d.search;
      d.page = d.start / d.length + 1;
      d.username = $('.username').val();
      d.id_user = id_user;
      d.lvl_user = lvl_user;
    }
  },
  columns: [{
    data: 'name',
    defaultContent: '',
    title: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Name')
  }, {
    data: 'username',
    defaultContent: '',
    title: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('User Name')
  }, {
    data: 'email',
    defaultContent: '',
    title: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Email')
  }, {
    data: 'created_at',
    defaultContent: '',
    title: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Time Created')
  }, {
    data: 'updated_at',
    defaultContent: '',
    title: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Time Updated')
  }, {
    data: 'id',
    title: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Action'),
    defaultContent: '',
    render: function render(data) {
      if (id_user == data || lvl_user == 9999) {
        var a = "";
        if (id_user == data) {
          a = a + "<a href=\"" + route_show + "?id=" + data + "\" class=\"btn btn-success\" style=\" width: 80px\">\n                                " + (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Edit') + "\n                            </a>";
        }
        if (lvl_user == 9999) {
          a = a + "<a href=\"" + route_show + "?id=" + data + "\" class=\"btn btn-success\" style=\" width: 80px\">\n                                " + (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Edit') + "\n                            </a>\n                            <button id=\"del-" + data + "\" class=\"btn btn-danger btn-delete\" style=\"width: 80px\">\n                            " + (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Delete') + "\n                            </button>\n                            ";
        }
        return a;
      } else {
        return "";
      }
    }
  }]
});
$('table').on('page.dt', function () {
  console.log(table.page.info());
});
$('.filter').on('click', function () {
  table.ajax.reload();
});
$(document).on('click', '.btn-delete', function () {
  var id = $(this).attr('id');
  var name = $(this).parent().parent().children('td').first().text();
  $('#modalRequestDel').modal();
  $('#nameDel').text(name);
  $('#idDel').val(id.split('-')[1]);
});
var tablehis = $('.table-his').DataTable(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty({
  scrollX: true,
  searching: false,
  ordering: false,
  language: {
    lengthMenu: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Number of records _MENU_'),
    info: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Showing _START_ to _END_ of _TOTAL_ entries'),
    paginate: {
      previous: '‹',
      next: '›'
    }
  },
  processing: true,
  serverSide: true
}, "searching", false), "dom", 'rt<"bottom"flp><"clear">'), "lengthMenu", [10, 20, 30, 40, 50]), "ajax", {
  url: route_his,
  dataSrc: 'data',
  data: function data(d) {
    delete d.columns;
    delete d.order;
    delete d.search;
    d.page = d.start / d.length + 1;
    d.shiftid = $('#idUnit').val();
  }
}), "columns", [
// { data: 'ID_Main', defaultContent: '', title: 'ID' },
{
  data: 'Data_Table',
  defaultContent: '',
  title: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Name'),
  render: function render(data) {
    var datas = JSON.parse(data);
    return datas.name;
  }
}, {
  data: 'Data_Table',
  defaultContent: '',
  title: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('User Name'),
  render: function render(data) {
    var datas = JSON.parse(data);
    return datas.username;
  }
}, {
  data: 'Data_Table',
  defaultContent: '',
  title: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Email'),
  render: function render(data) {
    var datas = JSON.parse(data);
    return datas.email;
  }
}, {
  data: 'Data_Table',
  defaultContent: '',
  title: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Role'),
  render: function render(data) {
    var datas = JSON.parse(data);
    return datas.role;
  }
}, {
  data: 'Data_Table',
  defaultContent: '',
  title: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Description'),
  render: function render(data) {
    var datas = JSON.parse(data);
    return datas.description;
  }
}, {
  data: 'Action_Name',
  defaultContent: '',
  title: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Action Name'),
  render: function render(data) {
    return (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])(data);
  }
}, {
  data: 'Data_Table',
  defaultContent: '',
  title: (0,_lang__WEBPACK_IMPORTED_MODULE_0__["default"])('Time Updated'),
  render: function render(data) {
    var datas = JSON.parse(data);
    var timeupdated = moment(datas.updated_at, 'MMMM Do YYYY h:mm:ss a').format('YYYY-MM-DD HH:mm:ss');
    return timeupdated;
  }
}]));
$(document).on('click', '.btn-history', function () {
  $('#modalTableHistory').modal();
  tablehis.ajax.reload();
});
})();

/******/ })()
;