(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["app"],{

/***/ "./app.ts":
/*!****************!*\
  !*** ./app.ts ***!
  \****************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

/**
 * app.ts
 *
 * Typescript
 *
 * Application entry point
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Sweelix
 * @license http://www.sweelix.net/license license
 * @version XXX
 * @link http://www.sweelix.net
 * @package application\assets\webpack
 */
Object.defineProperty(exports, "__esModule", { value: true });
var Application_1 = __webpack_require__(/*! ./app/Application */ "./app/Application.ts");
// Instanciate application
var application = new Application_1.Application();
// Run application
application.run();


/***/ }),

/***/ "./app/Application.ts":
/*!****************************!*\
  !*** ./app/Application.ts ***!
  \****************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

/**
 * Application.ts
 *
 * Typescript
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Sweelix
 * @license http://www.sweelix.net/license license
 * @version XXX
 * @link http://www.sweelix.net
 * @package application\assets\webpack
 */
Object.defineProperty(exports, "__esModule", { value: true });
/**
 * Application
 *
 * Main application class
 *
 * @author Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2017 Sweelix
 * @license http://www.sweelix.net/license license
 * @version XXX
 * @link http://www.sweelix.net
 * @package application\assets\webpack
 */
var Application = /** @class */ (function () {
    /**
     * Class contructor
     */
    function Application() {
        console.log('Construct class');
    }
    /**
     * Sample method
     */
    Application.prototype.run = function () {
        console.log('Running application');
    };
    return Application;
}());
exports.Application = Application;


/***/ })

},[["./app.ts","manifest"]]]);
//# sourceMappingURL=app.js.map