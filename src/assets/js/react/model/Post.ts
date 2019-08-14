import { Image } from './Image';
import { Trend } from './Trend';

export type Post = {
  id: string;
  body: string;
  amountOfComments: number;
  amountOfLikes: number;
  amountOfTrends: number;
  image: Image | null;
  postedBy: any; // TODO: ADD OTHER MODELS
  trends: Trend[];
  updatedAt: Date;
  createdAt: Date;
};
