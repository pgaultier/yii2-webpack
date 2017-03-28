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

import { Application } from './app/Application';

// Instanciate application
let application:Application = new Application();

// Run application
application.run();

