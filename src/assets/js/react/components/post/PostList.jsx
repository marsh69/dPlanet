import React, { useState, useEffect } from 'react';
import { PostElement } from './PostElement';

export const PostList = () => {
  const [posts, setPosts] = useState([]);

  useEffect(() => {
    fetch('/posts')
      .then(response => response.json())
      .then(response => {
        setPosts(response);
      });
  }, []);

  return posts.map((post, key) => <PostElement key={key} post={post} />);
};
