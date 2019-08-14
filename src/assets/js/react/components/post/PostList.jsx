import React, { useState, useEffect } from 'react';
import { axios } from '../../../modules/axios';
import { PostElement } from './PostElement';

// TODO: To jsx

export const PostList = () => {
  const [posts, setPosts] = useState([]);

  useEffect(() => {
    axios.get('/posts').then(({ data }) => {
      setPosts(data);
    });
  }, []);

  return posts.map((post, key) => <PostElement key={key} post={post} />);
};
