/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/updates.js":
/*!*********************************!*\
  !*** ./resources/js/updates.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("$(function () {\n  var columns = [{\n    data: 'title',\n    name: 'title'\n  }, {\n    data: 'created_at',\n    name: 'created_at'\n  }, {\n    data: 'remove_btn',\n    searchable: false,\n    orderable: false\n  }];\n  var table = $('#updates-table');\n  var url = table.data('url');\n  datatable();\n\n  function datatable() {\n    table.DataTable({\n      processing: true,\n      serverSide: true,\n      ajax: url,\n      columns: columns\n    });\n  }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvdXBkYXRlcy5qcz9lOTRhIl0sIm5hbWVzIjpbIiQiLCJjb2x1bW5zIiwiZGF0YSIsIm5hbWUiLCJzZWFyY2hhYmxlIiwib3JkZXJhYmxlIiwidGFibGUiLCJ1cmwiLCJkYXRhdGFibGUiLCJEYXRhVGFibGUiLCJwcm9jZXNzaW5nIiwic2VydmVyU2lkZSIsImFqYXgiXSwibWFwcGluZ3MiOiJBQUFBQSxDQUFDLENBQUMsWUFBVztBQUVULE1BQU1DLE9BQU8sR0FBRyxDQUNaO0FBQUVDLFFBQUksRUFBRSxPQUFSO0FBQWlCQyxRQUFJLEVBQUU7QUFBdkIsR0FEWSxFQUVaO0FBQUVELFFBQUksRUFBRSxZQUFSO0FBQXNCQyxRQUFJLEVBQUU7QUFBNUIsR0FGWSxFQUdaO0FBQUVELFFBQUksRUFBRSxZQUFSO0FBQXNCRSxjQUFVLEVBQUUsS0FBbEM7QUFBeUNDLGFBQVMsRUFBRTtBQUFwRCxHQUhZLENBQWhCO0FBS0EsTUFBTUMsS0FBSyxHQUFHTixDQUFDLENBQUMsZ0JBQUQsQ0FBZjtBQUNBLE1BQU1PLEdBQUcsR0FBR0QsS0FBSyxDQUFDSixJQUFOLENBQVcsS0FBWCxDQUFaO0FBRUFNLFdBQVM7O0FBRVQsV0FBU0EsU0FBVCxHQUFxQjtBQUNqQkYsU0FBSyxDQUFDRyxTQUFOLENBQWdCO0FBQ1pDLGdCQUFVLEVBQUUsSUFEQTtBQUVaQyxnQkFBVSxFQUFFLElBRkE7QUFHWkMsVUFBSSxFQUFFTCxHQUhNO0FBSVpOLGFBQU8sRUFBRUE7QUFKRyxLQUFoQjtBQU1IO0FBRUosQ0FyQkEsQ0FBRCIsImZpbGUiOiIuL3Jlc291cmNlcy9qcy91cGRhdGVzLmpzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiJChmdW5jdGlvbigpIHtcblxuICAgIGNvbnN0IGNvbHVtbnMgPSBbXG4gICAgICAgIHsgZGF0YTogJ3RpdGxlJywgbmFtZTogJ3RpdGxlJ30sXG4gICAgICAgIHsgZGF0YTogJ2NyZWF0ZWRfYXQnLCBuYW1lOiAnY3JlYXRlZF9hdCcgfSxcbiAgICAgICAgeyBkYXRhOiAncmVtb3ZlX2J0bicsIHNlYXJjaGFibGU6IGZhbHNlLCBvcmRlcmFibGU6IGZhbHNlLCB9LFxuICAgIF07XG4gICAgY29uc3QgdGFibGUgPSAkKCcjdXBkYXRlcy10YWJsZScpO1xuICAgIGNvbnN0IHVybCA9IHRhYmxlLmRhdGEoJ3VybCcpO1xuXG4gICAgZGF0YXRhYmxlKCk7XG5cbiAgICBmdW5jdGlvbiBkYXRhdGFibGUoKSB7XG4gICAgICAgIHRhYmxlLkRhdGFUYWJsZSh7XG4gICAgICAgICAgICBwcm9jZXNzaW5nOiB0cnVlLFxuICAgICAgICAgICAgc2VydmVyU2lkZTogdHJ1ZSxcbiAgICAgICAgICAgIGFqYXg6IHVybCxcbiAgICAgICAgICAgIGNvbHVtbnM6IGNvbHVtbnNcbiAgICAgICAgfSk7XG4gICAgfVxuXG59KTsiXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/updates.js\n");

/***/ }),

/***/ 2:
/*!***************************************!*\
  !*** multi ./resources/js/updates.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/jointoit/mypets/resources/js/updates.js */"./resources/js/updates.js");


/***/ })

/******/ });