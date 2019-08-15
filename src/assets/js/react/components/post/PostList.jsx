import React, { useState, useEffect } from 'react';
import { PostElement } from './PostElement';
import { fetchData } from '../../../modules/fetch';

export const PostList = () => {
  const [posts, setPosts] = useState([]);

  useEffect(() => {
    fetchData('/posts').then((data) => setPosts(data));
  }, []);

  return posts.map((post, key) => <PostElement key={key} post={post} />);
};
