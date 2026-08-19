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
	logger.debug('>>> Entering prefillJohnUser()');
    this.usernameTarget.value = 'john_user'
    this.passwordTarget.value = 'kitten'
	logger.debug('<<< Exiting prefillJohnUser()');
  }

  prefillJaneAdmin() {
	logger.debug('>>> Entering prefillJaneAdmin()');
    this.usernameTarget.value = 'jane_admin'
    this.passwordTarget.value = 'kitten'
	logger.debug('<<< Exiting prefillJaneAdmin()');
  }

  togglePasswordInputType() {
	logger.debug('>>> Entering togglePasswordInputType()');
    if ('password' === this.passwordTarget.type) {
      this.passwordTarget.type = 'text'
    } else {
      this.passwordTarget.type = 'password'
	logger.debug('<<< Exiting togglePasswordInputType()');
    }
  }
}
