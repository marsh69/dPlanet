import React, { useState } from 'react';
import { CommentElement } from '../comment/CommentElement';
import { formatDateDefault } from '../../../modules/stringUtils';

import '../../../../css/modules/post.scss';

export const PostElement = ({ post }) => {
  const [commentsVisible, setCommentsVisible] = useState(false);

  const toggleComments = () => setCommentsVisible(!commentsVisible);

  return (
    <div className="card post__card">
      <div className="card-header d-flex justify-content-between bg-info text-white">
        <span>
          <strong>{post.postedBy.username}</strong> posted:
        </span>
        <span className="text-muted">{formatDateDefault(post.createdAt)}</span>
      </div>
      <div className="card-body">
        {post.image ? (
          <img src={post.image.publicPath} alt={post.image.fileName} />
        ) : null}
        <p>{post.body}</p>
        <p className="text-muted">
          Trends: {post.trends.map((trend) => trend.name).join(', ')}
        </p>
        <button className="btn btn-link" onClick={toggleComments}>
          {post.amountOfComments} comments
        </button>
        <a href={`/posts/${post.id}/comments/new`}>
          <button className="btn btn-link">Post a comment</button>
        </a>
        {commentsVisible
          ? post.comments.map((comment) => (
              <CommentElement key={comment.id} comment={comment} />
            ))
          : null}
      </div>
    </div>
  );
};
