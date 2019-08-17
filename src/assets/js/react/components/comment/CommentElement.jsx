import * as React from 'react';
import { formatDateDefault } from '../../../modules/stringUtils';

import '../../../../css/modules/comment.scss';

export const CommentElement = ({ comment }) => {
  return (
    <div className="card comment__card">
      <div className="card-body">
        <p>{comment.body}</p>
        <div className="d-flex justify-content-between text-muted">
          <em>{comment.postedBy.username}</em>
          <span>{formatDateDefault(comment.createdAt)}</span>
        </div>
      </div>
    </div>
  );
};
