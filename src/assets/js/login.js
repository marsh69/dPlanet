import { domContentLoadedCallback } from './modules/domcontentloadercallback';
import { postFormData } from './modules/fetch';
import { selectElements } from './modules/easySelector';

const addEventListenerToLoginForm = () => {
  const { form, username, password, error } = selectElements({
    form: '#login-form',
    username: '#login-username',
    password: '#login-password',
    error: '#login-error',
  });

  const url = form.getAttribute('action');

  form.addEventListener('submit', (event) => {
    event.preventDefault();
    error.style.display = 'none';

    const loginData = {
      _username: username.value,
      _password: password.value,
    };

    postFormData(url, loginData).then((response) => {
      response.success
        ? handleLoginSuccess(response.target)
        : handleLoginError(error);
    });
  });
};

/**
 * @param {Element} errorElement
 */
const handleLoginError = (errorElement) => {
  errorElement.style.display = 'block';
};

/**
 * @param {string} target
 */
const handleLoginSuccess = (target) => (window.location.href = target);

domContentLoadedCallback([addEventListenerToLoginForm]);
