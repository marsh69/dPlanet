import React from 'react';
import ReactDOM from 'react-dom';

import { domContentLoadedCallback } from './modules/domcontentloadercallback';
import { PostList } from './react/components/post/PostList';

const reactRender = () => {
  ReactDOM.render(<PostList />, document.querySelector('#PostList'));
};

domContentLoadedCallback([reactRender]);
