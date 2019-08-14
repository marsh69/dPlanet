import * as React from 'react';
import {Post} from "../../model/Post";

type PostElementProps = {
    post: Post;
    key: number;
}

export const PostElement = ({post}: PostElementProps) => {
  const getPostImage = () =>
    post.image ? (
      <img src={post.image.publicPath} alt={post.image.fileName} />
    ) : null;

  return (
    <div className="card">
      <div className="card-header">{post.postedBy.fullName} posted:</div>
      <div className="card-body">
        {getPostImage()}
        <p>{post.body}</p>
        <p className="text-muted">
          Trends: {post.trends.map(trend => trend.name).join(', ')}
        </p>
      </div>
      <div className="card-footer">
        <button className="btn btn-default">
          {post.amountOfComments} comments
        </button>
      </div>
    </div>
  );
};
