import 'bootstrap-scss';
import { activateSelect2 } from './modules/select2';
import { domContentLoadedCallback } from './utils/domcontentloadercallback';

import '../images/backgrounds/logo.png';
import '../css/app.scss';

domContentLoadedCallback([activateSelect2]);
