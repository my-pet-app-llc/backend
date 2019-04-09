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

/***/ "./resources/js/helper.js":
/*!********************************!*\
  !*** ./resources/js/helper.js ***!
  \********************************/
/*! exports provided: showMessage */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"showMessage\", function() { return showMessage; });\nfunction showMessage(messageText) {\n  var className = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'alert_message';\n  var list = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : [];\n  var timeout = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 3000;\n  var div = document.getElementById('message_wrap') ? document.getElementById('message_wrap') : document.createElement('div');\n  div.setAttribute('id', 'message_wrap');\n  var renderedList = list.length ? list.map(function (el) {\n    return \"<li>\".concat(el, \"</li>\");\n  }) : \"\";\n  var messageBlock = document.createElement('div');\n  messageBlock.setAttribute('class', \"alert \".concat(className));\n  messageBlock.setAttribute('style', 'width: 260px; position: fixed; z-index: 9999; display: block; opacity: 0; right: 100px');\n  messageBlock.innerHTML = \"\\n        <strong>\".concat(messageText, \"</strong>\\n        <br><br>\\n        <ul>\\n          \").concat(renderedList, \"\\n        </ul>\\n         \");\n  div.appendChild(messageBlock);\n  animate(messageBlock, 'opacity', 0, 1, 500);\n  setTimeout(function () {\n    animate(messageBlock, 'opacity', 1, 0, 500);\n    setTimeout(function () {\n      return div.removeChild(messageBlock);\n    }, 1000);\n  }, timeout);\n  document.body.appendChild(div);\n}\n\nfunction animate(elem, property, startVal, endVal, time) {\n  var dimension = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : \"\";\n  var frame = 0;\n  var frameRate = 0.06;\n  var delta = (endVal - startVal) / time / frameRate;\n  var handle = setInterval(function () {\n    frame++;\n    var value = startVal + delta * frame;\n    elem.style[property] = value + dimension;\n\n    if (value == endVal) {\n      clearInterval(handle);\n    }\n  }, 1 / frameRate);\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvaGVscGVyLmpzPzUzYjYiXSwibmFtZXMiOlsic2hvd01lc3NhZ2UiLCJtZXNzYWdlVGV4dCIsImNsYXNzTmFtZSIsImxpc3QiLCJ0aW1lb3V0IiwiZGl2IiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsImNyZWF0ZUVsZW1lbnQiLCJzZXRBdHRyaWJ1dGUiLCJyZW5kZXJlZExpc3QiLCJsZW5ndGgiLCJtYXAiLCJlbCIsIm1lc3NhZ2VCbG9jayIsImlubmVySFRNTCIsImFwcGVuZENoaWxkIiwiYW5pbWF0ZSIsInNldFRpbWVvdXQiLCJyZW1vdmVDaGlsZCIsImJvZHkiLCJlbGVtIiwicHJvcGVydHkiLCJzdGFydFZhbCIsImVuZFZhbCIsInRpbWUiLCJkaW1lbnNpb24iLCJmcmFtZSIsImZyYW1lUmF0ZSIsImRlbHRhIiwiaGFuZGxlIiwic2V0SW50ZXJ2YWwiLCJ2YWx1ZSIsInN0eWxlIiwiY2xlYXJJbnRlcnZhbCJdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFPLFNBQVNBLFdBQVQsQ0FBcUJDLFdBQXJCLEVBQTBGO0FBQUEsTUFBeERDLFNBQXdELHVFQUE1QyxlQUE0QztBQUFBLE1BQTNCQyxJQUEyQix1RUFBcEIsRUFBb0I7QUFBQSxNQUFoQkMsT0FBZ0IsdUVBQU4sSUFBTTtBQUM3RixNQUFNQyxHQUFHLEdBQUdDLFFBQVEsQ0FBQ0MsY0FBVCxDQUF3QixjQUF4QixJQUNSRCxRQUFRLENBQUNDLGNBQVQsQ0FBd0IsY0FBeEIsQ0FEUSxHQUVSRCxRQUFRLENBQUNFLGFBQVQsQ0FBdUIsS0FBdkIsQ0FGSjtBQUdJSCxLQUFHLENBQUNJLFlBQUosQ0FBaUIsSUFBakIsRUFBdUIsY0FBdkI7QUFFSixNQUFNQyxZQUFZLEdBQUdQLElBQUksQ0FBQ1EsTUFBTCxHQUFjUixJQUFJLENBQUNTLEdBQUwsQ0FBUyxVQUFDQyxFQUFEO0FBQUEseUJBQWdCQSxFQUFoQjtBQUFBLEdBQVQsQ0FBZCxHQUFxRCxFQUExRTtBQUNBLE1BQU1DLFlBQVksR0FBR1IsUUFBUSxDQUFDRSxhQUFULENBQXVCLEtBQXZCLENBQXJCO0FBQ0lNLGNBQVksQ0FBQ0wsWUFBYixDQUEwQixPQUExQixrQkFBNkNQLFNBQTdDO0FBQ0FZLGNBQVksQ0FBQ0wsWUFBYixDQUEwQixPQUExQixFQUFtQyx3RkFBbkM7QUFDQUssY0FBWSxDQUFDQyxTQUFiLCtCQUVXZCxXQUZYLGtFQUtLUyxZQUxMO0FBUUFMLEtBQUcsQ0FBQ1csV0FBSixDQUFnQkYsWUFBaEI7QUFDQUcsU0FBTyxDQUFDSCxZQUFELEVBQWUsU0FBZixFQUEwQixDQUExQixFQUE2QixDQUE3QixFQUFnQyxHQUFoQyxDQUFQO0FBQ0pJLFlBQVUsQ0FBQyxZQUFNO0FBQ2JELFdBQU8sQ0FBQ0gsWUFBRCxFQUFlLFNBQWYsRUFBMEIsQ0FBMUIsRUFBNkIsQ0FBN0IsRUFBZ0MsR0FBaEMsQ0FBUDtBQUNBSSxjQUFVLENBQUM7QUFBQSxhQUFNYixHQUFHLENBQUNjLFdBQUosQ0FBZ0JMLFlBQWhCLENBQU47QUFBQSxLQUFELEVBQXNDLElBQXRDLENBQVY7QUFDSCxHQUhTLEVBR1BWLE9BSE8sQ0FBVjtBQUlBRSxVQUFRLENBQUNjLElBQVQsQ0FBY0osV0FBZCxDQUEwQlgsR0FBMUI7QUFDSDs7QUFFRCxTQUFTWSxPQUFULENBQWlCSSxJQUFqQixFQUF1QkMsUUFBdkIsRUFBaUNDLFFBQWpDLEVBQTJDQyxNQUEzQyxFQUFtREMsSUFBbkQsRUFBeUU7QUFBQSxNQUFoQkMsU0FBZ0IsdUVBQUosRUFBSTtBQUNyRSxNQUFJQyxLQUFLLEdBQUcsQ0FBWjtBQUNBLE1BQU1DLFNBQVMsR0FBRyxJQUFsQjtBQUNBLE1BQU1DLEtBQUssR0FBRyxDQUFDTCxNQUFNLEdBQUdELFFBQVYsSUFBc0JFLElBQXRCLEdBQTZCRyxTQUEzQztBQUNBLE1BQU1FLE1BQU0sR0FBR0MsV0FBVyxDQUFDLFlBQVc7QUFDbENKLFNBQUs7QUFDTCxRQUFJSyxLQUFLLEdBQUdULFFBQVEsR0FBR00sS0FBSyxHQUFHRixLQUEvQjtBQUNBTixRQUFJLENBQUNZLEtBQUwsQ0FBV1gsUUFBWCxJQUF1QlUsS0FBSyxHQUFHTixTQUEvQjs7QUFDQSxRQUFJTSxLQUFLLElBQUlSLE1BQWIsRUFBcUI7QUFDakJVLG1CQUFhLENBQUNKLE1BQUQsQ0FBYjtBQUNIO0FBQ0osR0FQeUIsRUFPdkIsSUFBSUYsU0FQbUIsQ0FBMUI7QUFRSCIsImZpbGUiOiIuL3Jlc291cmNlcy9qcy9oZWxwZXIuanMuanMiLCJzb3VyY2VzQ29udGVudCI6WyJleHBvcnQgZnVuY3Rpb24gc2hvd01lc3NhZ2UobWVzc2FnZVRleHQsIGNsYXNzTmFtZSA9ICdhbGVydF9tZXNzYWdlJywgbGlzdCA9IFtdLCB0aW1lb3V0ID0gMzAwMCkge1xuICAgIGNvbnN0IGRpdiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdtZXNzYWdlX3dyYXAnKSA/XG4gICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdtZXNzYWdlX3dyYXAnKSA6XG4gICAgICAgIGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICBkaXYuc2V0QXR0cmlidXRlKCdpZCcsICdtZXNzYWdlX3dyYXAnKTtcblxuICAgIGNvbnN0IHJlbmRlcmVkTGlzdCA9IGxpc3QubGVuZ3RoID8gbGlzdC5tYXAoKGVsKSA9PiBgPGxpPiR7IGVsIH08L2xpPmApIDogXCJcIjtcbiAgICBjb25zdCBtZXNzYWdlQmxvY2sgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKTtcbiAgICAgICAgbWVzc2FnZUJsb2NrLnNldEF0dHJpYnV0ZSgnY2xhc3MnLCBgYWxlcnQgJHsgY2xhc3NOYW1lIH1gKTtcbiAgICAgICAgbWVzc2FnZUJsb2NrLnNldEF0dHJpYnV0ZSgnc3R5bGUnLCAnd2lkdGg6IDI2MHB4OyBwb3NpdGlvbjogZml4ZWQ7IHotaW5kZXg6IDk5OTk7IGRpc3BsYXk6IGJsb2NrOyBvcGFjaXR5OiAwOyByaWdodDogMTAwcHgnKTtcbiAgICAgICAgbWVzc2FnZUJsb2NrLmlubmVySFRNTCA9XG4gICAgICAgIGBcbiAgICAgICAgPHN0cm9uZz4keyBtZXNzYWdlVGV4dCB9PC9zdHJvbmc+XG4gICAgICAgIDxicj48YnI+XG4gICAgICAgIDx1bD5cbiAgICAgICAgICAkeyByZW5kZXJlZExpc3QgfVxuICAgICAgICA8L3VsPlxuICAgICAgICAgYDtcbiAgICAgICAgZGl2LmFwcGVuZENoaWxkKG1lc3NhZ2VCbG9jayk7XG4gICAgICAgIGFuaW1hdGUobWVzc2FnZUJsb2NrLCAnb3BhY2l0eScsIDAsIDEsIDUwMCk7XG4gICAgc2V0VGltZW91dCgoKSA9PiB7XG4gICAgICAgIGFuaW1hdGUobWVzc2FnZUJsb2NrLCAnb3BhY2l0eScsIDEsIDAsIDUwMCk7XG4gICAgICAgIHNldFRpbWVvdXQoKCkgPT4gZGl2LnJlbW92ZUNoaWxkKG1lc3NhZ2VCbG9jayksIDEwMDApO1xuICAgIH0sIHRpbWVvdXQpO1xuICAgIGRvY3VtZW50LmJvZHkuYXBwZW5kQ2hpbGQoZGl2KTtcbn1cblxuZnVuY3Rpb24gYW5pbWF0ZShlbGVtLCBwcm9wZXJ0eSwgc3RhcnRWYWwsIGVuZFZhbCwgdGltZSwgZGltZW5zaW9uID0gXCJcIikge1xuICAgIGxldCBmcmFtZSA9IDA7XG4gICAgY29uc3QgZnJhbWVSYXRlID0gMC4wNjsgXG4gICAgY29uc3QgZGVsdGEgPSAoZW5kVmFsIC0gc3RhcnRWYWwpIC8gdGltZSAvIGZyYW1lUmF0ZTtcbiAgICBjb25zdCBoYW5kbGUgPSBzZXRJbnRlcnZhbChmdW5jdGlvbigpIHtcbiAgICAgICAgZnJhbWUrKztcbiAgICAgICAgbGV0IHZhbHVlID0gc3RhcnRWYWwgKyBkZWx0YSAqIGZyYW1lO1xuICAgICAgICBlbGVtLnN0eWxlW3Byb3BlcnR5XSA9IHZhbHVlICsgZGltZW5zaW9uO1xuICAgICAgICBpZiAodmFsdWUgPT0gZW5kVmFsKSB7XG4gICAgICAgICAgICBjbGVhckludGVydmFsKGhhbmRsZSk7XG4gICAgICAgIH1cbiAgICB9LCAxIC8gZnJhbWVSYXRlKTtcbn0iXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/helper.js\n");

/***/ }),

/***/ "./resources/js/updates.js":
/*!*********************************!*\
  !*** ./resources/js/updates.js ***!
  \*********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _helper_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helper.js */ \"./resources/js/helper.js\");\n\n$(function () {\n  var message = $('.flesh_message').data('message');\n  var columns = [{\n    data: 'title',\n    name: 'title'\n  }, {\n    data: 'created_at',\n    name: 'created_at'\n  }, {\n    data: 'remove_btn',\n    searchable: false,\n    orderable: false\n  }];\n  var table = $('#updates-table');\n  var url = table.data('url');\n  datatable();\n\n  function datatable() {\n    table.DataTable({\n      serverSide: true,\n      ajax: url,\n      columns: columns\n    });\n  }\n\n  $(document).on('click', '.delete_update', function (e) {\n    $('#deleteUpdate').modal();\n    var form = $(e.target).closest('form');\n    var url = form.attr('action');\n    var token = form.find('[name=\"_token\"]').val();\n    $('#removeUpdate').attr('data-route', url);\n    $('#removeUpdate').attr('data-token', token);\n  });\n  $('.remove_update').on('click', function (e) {\n    e.preventDefault();\n    var url = $(e.target).attr('data-route');\n    var token = $(e.target).attr('data-token');\n    var flashMessage = $(e.target).data('flash-message');\n    $.ajax({\n      type: 'DELETE',\n      url: url,\n      data: {\n        '_token': token\n      },\n      success: function success($data) {\n        $('#deleteUpdate').modal('hide');\n        Object(_helper_js__WEBPACK_IMPORTED_MODULE_0__[\"showMessage\"])(flashMessage);\n        table.DataTable().clear().draw();\n      },\n      error: function error($error) {}\n    });\n  });\n  $(document).ready(function () {\n    if (typeof message != \"undefined\") {\n      Object(_helper_js__WEBPACK_IMPORTED_MODULE_0__[\"showMessage\"])(message);\n    }\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvdXBkYXRlcy5qcz9lOTRhIl0sIm5hbWVzIjpbIiQiLCJtZXNzYWdlIiwiZGF0YSIsImNvbHVtbnMiLCJuYW1lIiwic2VhcmNoYWJsZSIsIm9yZGVyYWJsZSIsInRhYmxlIiwidXJsIiwiZGF0YXRhYmxlIiwiRGF0YVRhYmxlIiwic2VydmVyU2lkZSIsImFqYXgiLCJkb2N1bWVudCIsIm9uIiwiZSIsIm1vZGFsIiwiZm9ybSIsInRhcmdldCIsImNsb3Nlc3QiLCJhdHRyIiwidG9rZW4iLCJmaW5kIiwidmFsIiwicHJldmVudERlZmF1bHQiLCJmbGFzaE1lc3NhZ2UiLCJ0eXBlIiwic3VjY2VzcyIsIiRkYXRhIiwic2hvd01lc3NhZ2UiLCJjbGVhciIsImRyYXciLCJlcnJvciIsIiRlcnJvciIsInJlYWR5Il0sIm1hcHBpbmdzIjoiQUFBQTtBQUFBO0FBQUE7QUFFQUEsQ0FBQyxDQUFDLFlBQVc7QUFFVCxNQUFNQyxPQUFPLEdBQUdELENBQUMsQ0FBQyxnQkFBRCxDQUFELENBQW9CRSxJQUFwQixDQUF5QixTQUF6QixDQUFoQjtBQUNBLE1BQU1DLE9BQU8sR0FBRyxDQUNaO0FBQUVELFFBQUksRUFBRSxPQUFSO0FBQWlCRSxRQUFJLEVBQUU7QUFBdkIsR0FEWSxFQUVaO0FBQUVGLFFBQUksRUFBRSxZQUFSO0FBQXNCRSxRQUFJLEVBQUU7QUFBNUIsR0FGWSxFQUdaO0FBQUVGLFFBQUksRUFBRSxZQUFSO0FBQXNCRyxjQUFVLEVBQUUsS0FBbEM7QUFBeUNDLGFBQVMsRUFBRTtBQUFwRCxHQUhZLENBQWhCO0FBS0EsTUFBTUMsS0FBSyxHQUFHUCxDQUFDLENBQUMsZ0JBQUQsQ0FBZjtBQUNBLE1BQU1RLEdBQUcsR0FBR0QsS0FBSyxDQUFDTCxJQUFOLENBQVcsS0FBWCxDQUFaO0FBRUFPLFdBQVM7O0FBRVQsV0FBU0EsU0FBVCxHQUFxQjtBQUNqQkYsU0FBSyxDQUFDRyxTQUFOLENBQWdCO0FBQ1pDLGdCQUFVLEVBQUUsSUFEQTtBQUVaQyxVQUFJLEVBQUVKLEdBRk07QUFHWkwsYUFBTyxFQUFFQTtBQUhHLEtBQWhCO0FBS0g7O0FBRURILEdBQUMsQ0FBQ2EsUUFBRCxDQUFELENBQVlDLEVBQVosQ0FBZSxPQUFmLEVBQXdCLGdCQUF4QixFQUEwQyxVQUFVQyxDQUFWLEVBQWE7QUFDbkRmLEtBQUMsQ0FBQyxlQUFELENBQUQsQ0FBbUJnQixLQUFuQjtBQUNBLFFBQU1DLElBQUksR0FBR2pCLENBQUMsQ0FBQ2UsQ0FBQyxDQUFDRyxNQUFILENBQUQsQ0FBWUMsT0FBWixDQUFvQixNQUFwQixDQUFiO0FBQ0EsUUFBTVgsR0FBRyxHQUFHUyxJQUFJLENBQUNHLElBQUwsQ0FBVSxRQUFWLENBQVo7QUFDQSxRQUFNQyxLQUFLLEdBQUdKLElBQUksQ0FBQ0ssSUFBTCxDQUFVLGlCQUFWLEVBQTZCQyxHQUE3QixFQUFkO0FBRUF2QixLQUFDLENBQUMsZUFBRCxDQUFELENBQW1Cb0IsSUFBbkIsQ0FBd0IsWUFBeEIsRUFBc0NaLEdBQXRDO0FBQ0FSLEtBQUMsQ0FBQyxlQUFELENBQUQsQ0FBbUJvQixJQUFuQixDQUF3QixZQUF4QixFQUFzQ0MsS0FBdEM7QUFDSCxHQVJEO0FBVUFyQixHQUFDLENBQUMsZ0JBQUQsQ0FBRCxDQUFvQmMsRUFBcEIsQ0FBdUIsT0FBdkIsRUFBZ0MsVUFBVUMsQ0FBVixFQUFhO0FBQ3pDQSxLQUFDLENBQUNTLGNBQUY7QUFDQSxRQUFNaEIsR0FBRyxHQUFHUixDQUFDLENBQUNlLENBQUMsQ0FBQ0csTUFBSCxDQUFELENBQVlFLElBQVosQ0FBaUIsWUFBakIsQ0FBWjtBQUNBLFFBQU1DLEtBQUssR0FBR3JCLENBQUMsQ0FBQ2UsQ0FBQyxDQUFDRyxNQUFILENBQUQsQ0FBWUUsSUFBWixDQUFpQixZQUFqQixDQUFkO0FBQ0EsUUFBTUssWUFBWSxHQUFHekIsQ0FBQyxDQUFDZSxDQUFDLENBQUNHLE1BQUgsQ0FBRCxDQUFZaEIsSUFBWixDQUFpQixlQUFqQixDQUFyQjtBQUNBRixLQUFDLENBQUNZLElBQUYsQ0FBTztBQUNIYyxVQUFJLEVBQUUsUUFESDtBQUVIbEIsU0FBRyxFQUFHQSxHQUZIO0FBR0hOLFVBQUksRUFBRTtBQUFDLGtCQUFVbUI7QUFBWCxPQUhIO0FBSUhNLGFBQU8sRUFBRSxpQkFBU0MsS0FBVCxFQUFnQjtBQUNyQjVCLFNBQUMsQ0FBQyxlQUFELENBQUQsQ0FBbUJnQixLQUFuQixDQUF5QixNQUF6QjtBQUNBYSxzRUFBVyxDQUFDSixZQUFELENBQVg7QUFDQWxCLGFBQUssQ0FBQ0csU0FBTixHQUFrQm9CLEtBQWxCLEdBQTBCQyxJQUExQjtBQUNILE9BUkU7QUFTSEMsV0FBSyxFQUFFLGVBQVNDLE1BQVQsRUFBaUIsQ0FDdkI7QUFWRSxLQUFQO0FBWUgsR0FqQkQ7QUFtQkFqQyxHQUFDLENBQUNhLFFBQUQsQ0FBRCxDQUFZcUIsS0FBWixDQUFrQixZQUFZO0FBQzFCLFFBQUksT0FBT2pDLE9BQVAsSUFBbUIsV0FBdkIsRUFBb0M7QUFDaEM0QixvRUFBVyxDQUFDNUIsT0FBRCxDQUFYO0FBQ0g7QUFDSixHQUpEO0FBS0gsQ0F2REEsQ0FBRCIsImZpbGUiOiIuL3Jlc291cmNlcy9qcy91cGRhdGVzLmpzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtzaG93TWVzc2FnZX0gZnJvbSAnLi9oZWxwZXIuanMnXG5cbiQoZnVuY3Rpb24oKSB7XG5cbiAgICBjb25zdCBtZXNzYWdlID0gJCgnLmZsZXNoX21lc3NhZ2UnKS5kYXRhKCdtZXNzYWdlJyk7XG4gICAgY29uc3QgY29sdW1ucyA9IFtcbiAgICAgICAgeyBkYXRhOiAndGl0bGUnLCBuYW1lOiAndGl0bGUnIH0sXG4gICAgICAgIHsgZGF0YTogJ2NyZWF0ZWRfYXQnLCBuYW1lOiAnY3JlYXRlZF9hdCcgfSxcbiAgICAgICAgeyBkYXRhOiAncmVtb3ZlX2J0bicsIHNlYXJjaGFibGU6IGZhbHNlLCBvcmRlcmFibGU6IGZhbHNlLCB9LFxuICAgIF07XG4gICAgY29uc3QgdGFibGUgPSAkKCcjdXBkYXRlcy10YWJsZScpO1xuICAgIGNvbnN0IHVybCA9IHRhYmxlLmRhdGEoJ3VybCcpO1xuXG4gICAgZGF0YXRhYmxlKCk7XG5cbiAgICBmdW5jdGlvbiBkYXRhdGFibGUoKSB7XG4gICAgICAgIHRhYmxlLkRhdGFUYWJsZSh7XG4gICAgICAgICAgICBzZXJ2ZXJTaWRlOiB0cnVlLFxuICAgICAgICAgICAgYWpheDogdXJsLFxuICAgICAgICAgICAgY29sdW1uczogY29sdW1uc1xuICAgICAgICB9KTtcbiAgICB9XG5cbiAgICAkKGRvY3VtZW50KS5vbignY2xpY2snLCAnLmRlbGV0ZV91cGRhdGUnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAkKCcjZGVsZXRlVXBkYXRlJykubW9kYWwoKTtcbiAgICAgICAgY29uc3QgZm9ybSA9ICQoZS50YXJnZXQpLmNsb3Nlc3QoJ2Zvcm0nKTtcbiAgICAgICAgY29uc3QgdXJsID0gZm9ybS5hdHRyKCdhY3Rpb24nKTtcbiAgICAgICAgY29uc3QgdG9rZW4gPSBmb3JtLmZpbmQoJ1tuYW1lPVwiX3Rva2VuXCJdJykudmFsKCk7XG5cbiAgICAgICAgJCgnI3JlbW92ZVVwZGF0ZScpLmF0dHIoJ2RhdGEtcm91dGUnLCB1cmwpO1xuICAgICAgICAkKCcjcmVtb3ZlVXBkYXRlJykuYXR0cignZGF0YS10b2tlbicsIHRva2VuKTtcbiAgICB9KTtcblxuICAgICQoJy5yZW1vdmVfdXBkYXRlJykub24oJ2NsaWNrJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICBjb25zdCB1cmwgPSAkKGUudGFyZ2V0KS5hdHRyKCdkYXRhLXJvdXRlJyk7XG4gICAgICAgIGNvbnN0IHRva2VuID0gJChlLnRhcmdldCkuYXR0cignZGF0YS10b2tlbicpO1xuICAgICAgICBjb25zdCBmbGFzaE1lc3NhZ2UgPSAkKGUudGFyZ2V0KS5kYXRhKCdmbGFzaC1tZXNzYWdlJylcbiAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgIHR5cGU6ICdERUxFVEUnLFxuICAgICAgICAgICAgdXJsOiAgdXJsLFxuICAgICAgICAgICAgZGF0YTogeydfdG9rZW4nOiB0b2tlbn0sXG4gICAgICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbigkZGF0YSkge1xuICAgICAgICAgICAgICAgICQoJyNkZWxldGVVcGRhdGUnKS5tb2RhbCgnaGlkZScpO1xuICAgICAgICAgICAgICAgIHNob3dNZXNzYWdlKGZsYXNoTWVzc2FnZSk7ICBcbiAgICAgICAgICAgICAgICB0YWJsZS5EYXRhVGFibGUoKS5jbGVhcigpLmRyYXcoKTsgICAgXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgZXJyb3I6IGZ1bmN0aW9uKCRlcnJvcikge1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTsgXG4gICAgfSk7XG5cbiAgICAkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XG4gICAgICAgIGlmICh0eXBlb2YobWVzc2FnZSkgIT0gXCJ1bmRlZmluZWRcIikge1xuICAgICAgICAgICAgc2hvd01lc3NhZ2UobWVzc2FnZSk7IFxuICAgICAgICB9XG4gICAgfSk7XG59KTsiXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/updates.js\n");

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