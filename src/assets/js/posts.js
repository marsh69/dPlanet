import { axios } from './modules/axios';
import { domContentLoadedCallback } from './utils/domcontentloadercallback';

/**
 * Try out axios to see if a json response is returned
 */
const axiosDemo = () => {
  axios
    .get('/posts')
    .then(res => res.data)
    .then(jsonResult => console.log(jsonResult));
};

domContentLoadedCallback([axiosDemo]);
