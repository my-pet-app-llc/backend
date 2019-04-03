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
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"showMessage\", function() { return showMessage; });\nfunction showMessage(messageText) {\n  var className = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'alert_message';\n  var list = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : [];\n  var timeout = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 3000;\n  var div = document.getElementById('message_wrap') ? document.getElementById('message_wrap') : document.createElement('div');\n  div.setAttribute('id', 'message_wrap');\n  var renderedList = list.length ? list.map(function (el) {\n    return \"<li>\".concat(el, \"</li>\");\n  }) : \"\";\n  var messageBlock = document.createElement('div');\n  messageBlock.setAttribute('class', \"alert \".concat(className));\n  messageBlock.setAttribute('style', 'width: 250px; position: fixed; z-index: 9999; display: block; opacity: 0; right: 100px');\n  messageBlock.innerHTML = \"\\n        <strong>\".concat(messageText, \"</strong>\\n        <br><br>\\n        <ul>\\n          \").concat(renderedList, \"\\n        </ul>\\n         \");\n  div.appendChild(messageBlock);\n  animate(messageBlock, 'opacity', 0, 1, 500);\n  setTimeout(function () {\n    animate(messageBlock, 'opacity', 1, 0, 500);\n    setTimeout(function () {\n      return div.removeChild(messageBlock);\n    }, 1000);\n  }, timeout);\n  document.body.appendChild(div);\n}\n\nfunction animate(elem, property, startVal, endVal, time) {\n  var dimension = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : \"\";\n  var frame = 0;\n  var frameRate = 0.06;\n  var delta = (endVal - startVal) / time / frameRate;\n  var handle = setInterval(function () {\n    frame++;\n    var value = startVal + delta * frame;\n    elem.style[property] = value + dimension;\n\n    if (value == endVal) {\n      clearInterval(handle);\n    }\n  }, 1 / frameRate);\n}\n\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvaGVscGVyLmpzPzUzYjYiXSwibmFtZXMiOlsic2hvd01lc3NhZ2UiLCJtZXNzYWdlVGV4dCIsImNsYXNzTmFtZSIsImxpc3QiLCJ0aW1lb3V0IiwiZGl2IiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsImNyZWF0ZUVsZW1lbnQiLCJzZXRBdHRyaWJ1dGUiLCJyZW5kZXJlZExpc3QiLCJsZW5ndGgiLCJtYXAiLCJlbCIsIm1lc3NhZ2VCbG9jayIsImlubmVySFRNTCIsImFwcGVuZENoaWxkIiwiYW5pbWF0ZSIsInNldFRpbWVvdXQiLCJyZW1vdmVDaGlsZCIsImJvZHkiLCJlbGVtIiwicHJvcGVydHkiLCJzdGFydFZhbCIsImVuZFZhbCIsInRpbWUiLCJkaW1lbnNpb24iLCJmcmFtZSIsImZyYW1lUmF0ZSIsImRlbHRhIiwiaGFuZGxlIiwic2V0SW50ZXJ2YWwiLCJ2YWx1ZSIsInN0eWxlIiwiY2xlYXJJbnRlcnZhbCJdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBLFNBQVNBLFdBQVQsQ0FBcUJDLFdBQXJCLEVBQTBGO0FBQUEsTUFBeERDLFNBQXdELHVFQUE1QyxlQUE0QztBQUFBLE1BQTNCQyxJQUEyQix1RUFBcEIsRUFBb0I7QUFBQSxNQUFoQkMsT0FBZ0IsdUVBQU4sSUFBTTtBQUN0RixNQUFNQyxHQUFHLEdBQUdDLFFBQVEsQ0FBQ0MsY0FBVCxDQUF3QixjQUF4QixJQUNSRCxRQUFRLENBQUNDLGNBQVQsQ0FBd0IsY0FBeEIsQ0FEUSxHQUVSRCxRQUFRLENBQUNFLGFBQVQsQ0FBdUIsS0FBdkIsQ0FGSjtBQUdJSCxLQUFHLENBQUNJLFlBQUosQ0FBaUIsSUFBakIsRUFBdUIsY0FBdkI7QUFFSixNQUFNQyxZQUFZLEdBQUdQLElBQUksQ0FBQ1EsTUFBTCxHQUFjUixJQUFJLENBQUNTLEdBQUwsQ0FBUyxVQUFDQyxFQUFEO0FBQUEseUJBQWdCQSxFQUFoQjtBQUFBLEdBQVQsQ0FBZCxHQUFxRCxFQUExRTtBQUNBLE1BQU1DLFlBQVksR0FBR1IsUUFBUSxDQUFDRSxhQUFULENBQXVCLEtBQXZCLENBQXJCO0FBQ0lNLGNBQVksQ0FBQ0wsWUFBYixDQUEwQixPQUExQixrQkFBNkNQLFNBQTdDO0FBQ0FZLGNBQVksQ0FBQ0wsWUFBYixDQUEwQixPQUExQixFQUFtQyx3RkFBbkM7QUFDQUssY0FBWSxDQUFDQyxTQUFiLCtCQUVXZCxXQUZYLGtFQUtLUyxZQUxMO0FBUUFMLEtBQUcsQ0FBQ1csV0FBSixDQUFnQkYsWUFBaEI7QUFDQUcsU0FBTyxDQUFDSCxZQUFELEVBQWUsU0FBZixFQUEwQixDQUExQixFQUE2QixDQUE3QixFQUFnQyxHQUFoQyxDQUFQO0FBQ0pJLFlBQVUsQ0FBQyxZQUFNO0FBQ2JELFdBQU8sQ0FBQ0gsWUFBRCxFQUFlLFNBQWYsRUFBMEIsQ0FBMUIsRUFBNkIsQ0FBN0IsRUFBZ0MsR0FBaEMsQ0FBUDtBQUNBSSxjQUFVLENBQUM7QUFBQSxhQUFNYixHQUFHLENBQUNjLFdBQUosQ0FBZ0JMLFlBQWhCLENBQU47QUFBQSxLQUFELEVBQXNDLElBQXRDLENBQVY7QUFDSCxHQUhTLEVBR1BWLE9BSE8sQ0FBVjtBQUlBRSxVQUFRLENBQUNjLElBQVQsQ0FBY0osV0FBZCxDQUEwQlgsR0FBMUI7QUFDSDs7QUFFRCxTQUFTWSxPQUFULENBQWlCSSxJQUFqQixFQUF1QkMsUUFBdkIsRUFBaUNDLFFBQWpDLEVBQTJDQyxNQUEzQyxFQUFtREMsSUFBbkQsRUFBeUU7QUFBQSxNQUFoQkMsU0FBZ0IsdUVBQUosRUFBSTtBQUNyRSxNQUFJQyxLQUFLLEdBQUcsQ0FBWjtBQUNBLE1BQU1DLFNBQVMsR0FBRyxJQUFsQjtBQUNBLE1BQU1DLEtBQUssR0FBRyxDQUFDTCxNQUFNLEdBQUdELFFBQVYsSUFBc0JFLElBQXRCLEdBQTZCRyxTQUEzQztBQUNBLE1BQU1FLE1BQU0sR0FBR0MsV0FBVyxDQUFDLFlBQVc7QUFDbENKLFNBQUs7QUFDTCxRQUFJSyxLQUFLLEdBQUdULFFBQVEsR0FBR00sS0FBSyxHQUFHRixLQUEvQjtBQUNBTixRQUFJLENBQUNZLEtBQUwsQ0FBV1gsUUFBWCxJQUF1QlUsS0FBSyxHQUFHTixTQUEvQjs7QUFDQSxRQUFJTSxLQUFLLElBQUlSLE1BQWIsRUFBcUI7QUFDakJVLG1CQUFhLENBQUNKLE1BQUQsQ0FBYjtBQUNIO0FBQ0osR0FQeUIsRUFPdkIsSUFBSUYsU0FQbUIsQ0FBMUI7QUFRSCIsImZpbGUiOiIuL3Jlc291cmNlcy9qcy9oZWxwZXIuanMuanMiLCJzb3VyY2VzQ29udGVudCI6WyJmdW5jdGlvbiBzaG93TWVzc2FnZShtZXNzYWdlVGV4dCwgY2xhc3NOYW1lID0gJ2FsZXJ0X21lc3NhZ2UnLCBsaXN0ID0gW10sIHRpbWVvdXQgPSAzMDAwKSB7XG4gICAgY29uc3QgZGl2ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ21lc3NhZ2Vfd3JhcCcpID9cbiAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ21lc3NhZ2Vfd3JhcCcpIDpcbiAgICAgICAgZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG4gICAgICAgIGRpdi5zZXRBdHRyaWJ1dGUoJ2lkJywgJ21lc3NhZ2Vfd3JhcCcpO1xuXG4gICAgY29uc3QgcmVuZGVyZWRMaXN0ID0gbGlzdC5sZW5ndGggPyBsaXN0Lm1hcCgoZWwpID0+IGA8bGk+JHsgZWwgfTwvbGk+YCkgOiBcIlwiO1xuICAgIGNvbnN0IG1lc3NhZ2VCbG9jayA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICAgICAgICBtZXNzYWdlQmxvY2suc2V0QXR0cmlidXRlKCdjbGFzcycsIGBhbGVydCAkeyBjbGFzc05hbWUgfWApO1xuICAgICAgICBtZXNzYWdlQmxvY2suc2V0QXR0cmlidXRlKCdzdHlsZScsICd3aWR0aDogMjUwcHg7IHBvc2l0aW9uOiBmaXhlZDsgei1pbmRleDogOTk5OTsgZGlzcGxheTogYmxvY2s7IG9wYWNpdHk6IDA7IHJpZ2h0OiAxMDBweCcpO1xuICAgICAgICBtZXNzYWdlQmxvY2suaW5uZXJIVE1MID1cbiAgICAgICAgYFxuICAgICAgICA8c3Ryb25nPiR7IG1lc3NhZ2VUZXh0IH08L3N0cm9uZz5cbiAgICAgICAgPGJyPjxicj5cbiAgICAgICAgPHVsPlxuICAgICAgICAgICR7IHJlbmRlcmVkTGlzdCB9XG4gICAgICAgIDwvdWw+XG4gICAgICAgICBgO1xuICAgICAgICBkaXYuYXBwZW5kQ2hpbGQobWVzc2FnZUJsb2NrKTtcbiAgICAgICAgYW5pbWF0ZShtZXNzYWdlQmxvY2ssICdvcGFjaXR5JywgMCwgMSwgNTAwKTtcbiAgICBzZXRUaW1lb3V0KCgpID0+IHtcbiAgICAgICAgYW5pbWF0ZShtZXNzYWdlQmxvY2ssICdvcGFjaXR5JywgMSwgMCwgNTAwKTtcbiAgICAgICAgc2V0VGltZW91dCgoKSA9PiBkaXYucmVtb3ZlQ2hpbGQobWVzc2FnZUJsb2NrKSwgMTAwMCk7XG4gICAgfSwgdGltZW91dCk7XG4gICAgZG9jdW1lbnQuYm9keS5hcHBlbmRDaGlsZChkaXYpO1xufVxuXG5mdW5jdGlvbiBhbmltYXRlKGVsZW0sIHByb3BlcnR5LCBzdGFydFZhbCwgZW5kVmFsLCB0aW1lLCBkaW1lbnNpb24gPSBcIlwiKSB7XG4gICAgbGV0IGZyYW1lID0gMDtcbiAgICBjb25zdCBmcmFtZVJhdGUgPSAwLjA2OyBcbiAgICBjb25zdCBkZWx0YSA9IChlbmRWYWwgLSBzdGFydFZhbCkgLyB0aW1lIC8gZnJhbWVSYXRlO1xuICAgIGNvbnN0IGhhbmRsZSA9IHNldEludGVydmFsKGZ1bmN0aW9uKCkge1xuICAgICAgICBmcmFtZSsrO1xuICAgICAgICBsZXQgdmFsdWUgPSBzdGFydFZhbCArIGRlbHRhICogZnJhbWU7XG4gICAgICAgIGVsZW0uc3R5bGVbcHJvcGVydHldID0gdmFsdWUgKyBkaW1lbnNpb247XG4gICAgICAgIGlmICh2YWx1ZSA9PSBlbmRWYWwpIHtcbiAgICAgICAgICAgIGNsZWFySW50ZXJ2YWwoaGFuZGxlKTtcbiAgICAgICAgfVxuICAgIH0sIDEgLyBmcmFtZVJhdGUpO1xufVxuXG5leHBvcnQge3Nob3dNZXNzYWdlfSJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/helper.js\n");

/***/ }),

/***/ "./resources/js/updates.js":
/*!*********************************!*\
  !*** ./resources/js/updates.js ***!
  \*********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _helper_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helper.js */ \"./resources/js/helper.js\");\n\n$(function () {\n  var message = $('.flesh_message').data('message');\n  var columns = [{\n    data: 'title',\n    name: 'title'\n  }, {\n    data: 'created_at',\n    name: 'created_at'\n  }, {\n    data: 'remove_btn',\n    searchable: false,\n    orderable: false\n  }];\n  var table = $('#updates-table');\n  var url = table.data('url');\n  datatable();\n\n  function datatable() {\n    table.DataTable({\n      processing: true,\n      serverSide: true,\n      ajax: url,\n      columns: columns\n    });\n  }\n\n  $(document).on('click', '.remove_update', function (e) {\n    $('#deleteUpdate').modal();\n    var form = $(e.target).closest('form');\n    var url = form.attr('action');\n    var token = form.find('[name=\"_token\"]').val();\n    $('#removeUpdate').attr('data-route', url);\n    $('#removeUpdate').attr('data-token', token);\n  });\n  $('.remove_update').on('click', function (e) {\n    e.preventDefault();\n    var url = $(e.target).attr('data-route');\n    var token = $(e.target).attr('data-token');\n    var flashMessage = $(e.target).data('flash-message');\n    $.ajax({\n      type: 'DELETE',\n      url: url,\n      data: {\n        '_token': token\n      },\n      success: function success($data) {\n        $('#deleteUpdate').modal('hide');\n        Object(_helper_js__WEBPACK_IMPORTED_MODULE_0__[\"showMessage\"])(flashMessage);\n        table.DataTable().clear().draw();\n      },\n      error: function error($error) {}\n    });\n  });\n  $(document).ready(function () {\n    if (typeof message != \"undefined\") {\n      Object(_helper_js__WEBPACK_IMPORTED_MODULE_0__[\"showMessage\"])(message);\n    }\n  });\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvdXBkYXRlcy5qcz9lOTRhIl0sIm5hbWVzIjpbIiQiLCJtZXNzYWdlIiwiZGF0YSIsImNvbHVtbnMiLCJuYW1lIiwic2VhcmNoYWJsZSIsIm9yZGVyYWJsZSIsInRhYmxlIiwidXJsIiwiZGF0YXRhYmxlIiwiRGF0YVRhYmxlIiwicHJvY2Vzc2luZyIsInNlcnZlclNpZGUiLCJhamF4IiwiZG9jdW1lbnQiLCJvbiIsImUiLCJtb2RhbCIsImZvcm0iLCJ0YXJnZXQiLCJjbG9zZXN0IiwiYXR0ciIsInRva2VuIiwiZmluZCIsInZhbCIsInByZXZlbnREZWZhdWx0IiwiZmxhc2hNZXNzYWdlIiwidHlwZSIsInN1Y2Nlc3MiLCIkZGF0YSIsInNob3dNZXNzYWdlIiwiY2xlYXIiLCJkcmF3IiwiZXJyb3IiLCIkZXJyb3IiLCJyZWFkeSJdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FBRUFBLENBQUMsQ0FBQyxZQUFXO0FBRVQsTUFBTUMsT0FBTyxHQUFHRCxDQUFDLENBQUMsZ0JBQUQsQ0FBRCxDQUFvQkUsSUFBcEIsQ0FBeUIsU0FBekIsQ0FBaEI7QUFDQSxNQUFNQyxPQUFPLEdBQUcsQ0FDWjtBQUFFRCxRQUFJLEVBQUUsT0FBUjtBQUFpQkUsUUFBSSxFQUFFO0FBQXZCLEdBRFksRUFFWjtBQUFFRixRQUFJLEVBQUUsWUFBUjtBQUFzQkUsUUFBSSxFQUFFO0FBQTVCLEdBRlksRUFHWjtBQUFFRixRQUFJLEVBQUUsWUFBUjtBQUFzQkcsY0FBVSxFQUFFLEtBQWxDO0FBQXlDQyxhQUFTLEVBQUU7QUFBcEQsR0FIWSxDQUFoQjtBQUtBLE1BQU1DLEtBQUssR0FBR1AsQ0FBQyxDQUFDLGdCQUFELENBQWY7QUFDQSxNQUFNUSxHQUFHLEdBQUdELEtBQUssQ0FBQ0wsSUFBTixDQUFXLEtBQVgsQ0FBWjtBQUVBTyxXQUFTOztBQUVULFdBQVNBLFNBQVQsR0FBcUI7QUFDakJGLFNBQUssQ0FBQ0csU0FBTixDQUFnQjtBQUNaQyxnQkFBVSxFQUFFLElBREE7QUFFWkMsZ0JBQVUsRUFBRSxJQUZBO0FBR1pDLFVBQUksRUFBRUwsR0FITTtBQUlaTCxhQUFPLEVBQUVBO0FBSkcsS0FBaEI7QUFNSDs7QUFFREgsR0FBQyxDQUFDYyxRQUFELENBQUQsQ0FBWUMsRUFBWixDQUFlLE9BQWYsRUFBd0IsZ0JBQXhCLEVBQTBDLFVBQVVDLENBQVYsRUFBYTtBQUNuRGhCLEtBQUMsQ0FBQyxlQUFELENBQUQsQ0FBbUJpQixLQUFuQjtBQUNBLFFBQU1DLElBQUksR0FBR2xCLENBQUMsQ0FBQ2dCLENBQUMsQ0FBQ0csTUFBSCxDQUFELENBQVlDLE9BQVosQ0FBb0IsTUFBcEIsQ0FBYjtBQUNBLFFBQU1aLEdBQUcsR0FBR1UsSUFBSSxDQUFDRyxJQUFMLENBQVUsUUFBVixDQUFaO0FBQ0EsUUFBTUMsS0FBSyxHQUFHSixJQUFJLENBQUNLLElBQUwsQ0FBVSxpQkFBVixFQUE2QkMsR0FBN0IsRUFBZDtBQUVBeEIsS0FBQyxDQUFDLGVBQUQsQ0FBRCxDQUFtQnFCLElBQW5CLENBQXdCLFlBQXhCLEVBQXNDYixHQUF0QztBQUNBUixLQUFDLENBQUMsZUFBRCxDQUFELENBQW1CcUIsSUFBbkIsQ0FBd0IsWUFBeEIsRUFBc0NDLEtBQXRDO0FBQ0gsR0FSRDtBQVlBdEIsR0FBQyxDQUFDLGdCQUFELENBQUQsQ0FBb0JlLEVBQXBCLENBQXVCLE9BQXZCLEVBQWdDLFVBQVVDLENBQVYsRUFBYTtBQUN6Q0EsS0FBQyxDQUFDUyxjQUFGO0FBQ0EsUUFBTWpCLEdBQUcsR0FBR1IsQ0FBQyxDQUFDZ0IsQ0FBQyxDQUFDRyxNQUFILENBQUQsQ0FBWUUsSUFBWixDQUFpQixZQUFqQixDQUFaO0FBQ0EsUUFBTUMsS0FBSyxHQUFHdEIsQ0FBQyxDQUFDZ0IsQ0FBQyxDQUFDRyxNQUFILENBQUQsQ0FBWUUsSUFBWixDQUFpQixZQUFqQixDQUFkO0FBQ0EsUUFBTUssWUFBWSxHQUFHMUIsQ0FBQyxDQUFDZ0IsQ0FBQyxDQUFDRyxNQUFILENBQUQsQ0FBWWpCLElBQVosQ0FBaUIsZUFBakIsQ0FBckI7QUFDQUYsS0FBQyxDQUFDYSxJQUFGLENBQU87QUFDSGMsVUFBSSxFQUFFLFFBREg7QUFFSG5CLFNBQUcsRUFBR0EsR0FGSDtBQUdITixVQUFJLEVBQUU7QUFBQyxrQkFBVW9CO0FBQVgsT0FISDtBQUlITSxhQUFPLEVBQUUsaUJBQVNDLEtBQVQsRUFBZ0I7QUFDckI3QixTQUFDLENBQUMsZUFBRCxDQUFELENBQW1CaUIsS0FBbkIsQ0FBeUIsTUFBekI7QUFDQWEsc0VBQVcsQ0FBQ0osWUFBRCxDQUFYO0FBQ0FuQixhQUFLLENBQUNHLFNBQU4sR0FBa0JxQixLQUFsQixHQUEwQkMsSUFBMUI7QUFDSCxPQVJFO0FBU0hDLFdBQUssRUFBRSxlQUFTQyxNQUFULEVBQWlCLENBQ3ZCO0FBVkUsS0FBUDtBQVlILEdBakJEO0FBbUJBbEMsR0FBQyxDQUFDYyxRQUFELENBQUQsQ0FBWXFCLEtBQVosQ0FBa0IsWUFBWTtBQUMxQixRQUFJLE9BQU9sQyxPQUFQLElBQW1CLFdBQXZCLEVBQW9DO0FBQ2hDNkIsb0VBQVcsQ0FBQzdCLE9BQUQsQ0FBWDtBQUNIO0FBQ0osR0FKRDtBQUtILENBMURBLENBQUQiLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvdXBkYXRlcy5qcy5qcyIsInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7c2hvd01lc3NhZ2V9IGZyb20gJy4vaGVscGVyLmpzJ1xuXG4kKGZ1bmN0aW9uKCkge1xuXG4gICAgY29uc3QgbWVzc2FnZSA9ICQoJy5mbGVzaF9tZXNzYWdlJykuZGF0YSgnbWVzc2FnZScpO1xuICAgIGNvbnN0IGNvbHVtbnMgPSBbXG4gICAgICAgIHsgZGF0YTogJ3RpdGxlJywgbmFtZTogJ3RpdGxlJyB9LFxuICAgICAgICB7IGRhdGE6ICdjcmVhdGVkX2F0JywgbmFtZTogJ2NyZWF0ZWRfYXQnIH0sXG4gICAgICAgIHsgZGF0YTogJ3JlbW92ZV9idG4nLCBzZWFyY2hhYmxlOiBmYWxzZSwgb3JkZXJhYmxlOiBmYWxzZSwgfSxcbiAgICBdO1xuICAgIGNvbnN0IHRhYmxlID0gJCgnI3VwZGF0ZXMtdGFibGUnKTtcbiAgICBjb25zdCB1cmwgPSB0YWJsZS5kYXRhKCd1cmwnKTtcblxuICAgIGRhdGF0YWJsZSgpO1xuXG4gICAgZnVuY3Rpb24gZGF0YXRhYmxlKCkge1xuICAgICAgICB0YWJsZS5EYXRhVGFibGUoe1xuICAgICAgICAgICAgcHJvY2Vzc2luZzogdHJ1ZSxcbiAgICAgICAgICAgIHNlcnZlclNpZGU6IHRydWUsXG4gICAgICAgICAgICBhamF4OiB1cmwsXG4gICAgICAgICAgICBjb2x1bW5zOiBjb2x1bW5zXG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgICQoZG9jdW1lbnQpLm9uKCdjbGljaycsICcucmVtb3ZlX3VwZGF0ZScsIGZ1bmN0aW9uIChlKSB7XG4gICAgICAgICQoJyNkZWxldGVVcGRhdGUnKS5tb2RhbCgpO1xuICAgICAgICBjb25zdCBmb3JtID0gJChlLnRhcmdldCkuY2xvc2VzdCgnZm9ybScpO1xuICAgICAgICBjb25zdCB1cmwgPSBmb3JtLmF0dHIoJ2FjdGlvbicpO1xuICAgICAgICBjb25zdCB0b2tlbiA9IGZvcm0uZmluZCgnW25hbWU9XCJfdG9rZW5cIl0nKS52YWwoKTtcblxuICAgICAgICAkKCcjcmVtb3ZlVXBkYXRlJykuYXR0cignZGF0YS1yb3V0ZScsIHVybCk7XG4gICAgICAgICQoJyNyZW1vdmVVcGRhdGUnKS5hdHRyKCdkYXRhLXRva2VuJywgdG9rZW4pO1xuICAgIH0pO1xuXG4gICAgICBcblxuICAgICQoJy5yZW1vdmVfdXBkYXRlJykub24oJ2NsaWNrJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICBjb25zdCB1cmwgPSAkKGUudGFyZ2V0KS5hdHRyKCdkYXRhLXJvdXRlJyk7XG4gICAgICAgIGNvbnN0IHRva2VuID0gJChlLnRhcmdldCkuYXR0cignZGF0YS10b2tlbicpO1xuICAgICAgICBjb25zdCBmbGFzaE1lc3NhZ2UgPSAkKGUudGFyZ2V0KS5kYXRhKCdmbGFzaC1tZXNzYWdlJylcbiAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgIHR5cGU6ICdERUxFVEUnLFxuICAgICAgICAgICAgdXJsOiAgdXJsLFxuICAgICAgICAgICAgZGF0YTogeydfdG9rZW4nOiB0b2tlbn0sXG4gICAgICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbigkZGF0YSkge1xuICAgICAgICAgICAgICAgICQoJyNkZWxldGVVcGRhdGUnKS5tb2RhbCgnaGlkZScpO1xuICAgICAgICAgICAgICAgIHNob3dNZXNzYWdlKGZsYXNoTWVzc2FnZSk7ICBcbiAgICAgICAgICAgICAgICB0YWJsZS5EYXRhVGFibGUoKS5jbGVhcigpLmRyYXcoKTsgICAgXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgZXJyb3I6IGZ1bmN0aW9uKCRlcnJvcikge1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTsgXG4gICAgfSk7XG5cbiAgICAkKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XG4gICAgICAgIGlmICh0eXBlb2YobWVzc2FnZSkgIT0gXCJ1bmRlZmluZWRcIikge1xuICAgICAgICAgICAgc2hvd01lc3NhZ2UobWVzc2FnZSk7IFxuICAgICAgICB9XG4gICAgfSk7XG59KTsiXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/updates.js\n");

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