webpackJsonp([0],[
/* 0 */
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
var Application = (function () {
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


/***/ }),
/* 1 */
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
var Application_1 = __webpack_require__(0);
// Instanciate application
var application = new Application_1.Application();
// Run application
application.run();


/***/ })
],[1]);
//# sourceMappingURL=app.js.map