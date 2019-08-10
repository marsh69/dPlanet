import axiosLib from 'axios';

export const axios = axiosLib.create({
  headers: {
    accept: 'application/json',
  },
});
