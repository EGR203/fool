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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 41);
/******/ })
/************************************************************************/
/******/ ({

/***/ 41:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(51);


/***/ }),

/***/ 51:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(52);

/***/ }),

/***/ 52:
/***/ (function(module, exports) {

Admin.Modules.register('admin-custom.proxy-ping-btn', function () {
    $('.proxy-ping-btn').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.data('isLoading')) {
            return;
        } else {
            $this.data('isLoading', true);
        }
        var $form = $this.parent();
        var url = $form.attr('action');
        var method = $form.attr('method');
        var data = {
            url: url,
            method: method,
            full: false,
            data: {}
        };
        $this.css('background-color', 'black');
        $this.css('color', 'black');
        $.ajax({
            type: 'POST',
            url: '/proxy',
            dataType: 'json',
            data: data,
            complete: function complete(resp) {
                console.log(resp.responseText);
                console.log(resp.status);
                if (resp.status >= 200 && resp.status < 300) {
                    $this.css('background-color', 'green');
                    $this.css('color', 'white');
                } else if (resp.status < 400) {
                    $this.css('background-color', 'yellow');
                    $this.css('color', 'black');
                } else {
                    $this.css('background-color', 'red');
                    $this.css('color', 'black');
                }

                $this.data('isLoading', false);
            }
        });
    });
});

/***/ })

/******/ });