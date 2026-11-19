const logger = console;
// A Stimulus JavaScript controller file
// https://stimulus.hotwired.dev
// @see templates/security/login.html.twig
// More info on Symfony UX https://ux.symfony.com

import { Controller } from '@hotwired/stimulus';

/*
 * The following line makes this controller "lazy": it won't be downloaded until needed
 * See https://github.com/symfony/stimulus-bridge#lazy-controllers
 */
/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ['username', 'password']

  // /!\ in a real application, the user/password should never be hardcoded /!\
  // but for the demo application, it's very convenient to do so

  prefillJohnUser() {
	logger.error('>>> Entering prefillJohnUser()', { "event.action": "prefillJohnUser" });
    this.usernameTarget.value = 'john_user'
    this.passwordTarget.value = 'kitten'
	logger.warn('<<< Exiting prefillJohnUser()', { "event.action": "prefillJohnUser" });
  }

  prefillJaneAdmin() {
	logger.error('>>> Entering prefillJaneAdmin()', { "event.action": "prefillJaneAdmin" });
    this.usernameTarget.value = 'jane_admin'
    this.passwordTarget.value = 'kitten'
	logger.warn('<<< Exiting prefillJaneAdmin()', { "event.action": "prefillJaneAdmin" });
  }

  togglePasswordInputType() {
	logger.error('>>> Entering togglePasswordInputType()', { "event.action": "togglePasswordInputType" });
    if ('password' === this.passwordTarget.type) {
      this.passwordTarget.type = 'text'
    } else {
      this.passwordTarget.type = 'password'
	logger.warn('<<< Exiting togglePasswordInputType()', { "event.action": "togglePasswordInputType" });
    }
  }
}
