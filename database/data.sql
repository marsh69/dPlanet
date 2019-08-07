-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 07, 2019 at 08:22 AM
-- Server version: 10.2.25-MariaDB
-- PHP Version: 7.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u502490984_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_body` text NOT NULL,
  `posted_by` varchar(60) NOT NULL,
  `posted_to` varchar(60) NOT NULL,
  `date_added` datetime NOT NULL,
  `removed` varchar(3) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL,
  `user_to` varchar(50) NOT NULL,
  `user_from` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friend_requests`
--

INSERT INTO `friend_requests` (`id`, `user_to`, `user_from`) VALUES
(18, 'aa_aa', 'ishank_sharma');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_to` varchar(50) NOT NULL,
  `user_from` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `date` datetime NOT NULL,
  `opened` varchar(3) NOT NULL,
  `viewed` varchar(3) NOT NULL,
  `deleted` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_to`, `user_from`, `body`, `date`, `opened`, `viewed`, `deleted`) VALUES
(65, 'aa_aa', 'ishank_sharma', 'ummmm', '2019-08-06 23:58:27', 'no', 'no', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_to` varchar(50) NOT NULL,
  `user_from` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(100) NOT NULL,
  `datetime` datetime NOT NULL,
  `opened` varchar(3) NOT NULL,
  `viewed` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `body` text NOT NULL,
  `added_by` varchar(60) NOT NULL,
  `user_to` varchar(60) NOT NULL,
  `date_added` datetime NOT NULL,
  `user_closed` varchar(3) NOT NULL,
  `deleted` varchar(3) NOT NULL,
  `likes` int(11) NOT NULL,
  `image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `body`, `added_by`, `user_to`, `date_added`, `user_closed`, `deleted`, `likes`, `image`) VALUES
(125, 'No', 'aa_aa', 'none', '2019-08-06 19:56:15', 'no', 'no', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `trends`
--

CREATE TABLE `trends` (
  `title` varchar(50) NOT NULL,
  `hits` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trends`
--

INSERT INTO `trends` (`title`, `hits`) VALUES
('Machine', 10),
('Learning', 10),
('Asas', 3),
('Ishank', 6),
('Sharma', 1),
('Sasas', 1),
('Yess', 1),
('Earth', 2),
('Beautifulrnrn', 1),
('Drfdf', 1),
('Saas', 1),
('Picture', 1),
('Universe', 2),
('Repost', 2),
('Panda', 2),
('Googles', 1),
('Selfdriving', 1),
('Cars', 1),
('Robots', 1),
('Lot', 1),
('Press', 1),
('Companys', 1),
('Real', 1),
('Future', 1),
('Machine', 5),
('Learning', 5),
('Technology', 1),
('Enables', 1),
('Computers', 1),
('Smarter', 1),
('Personalrnrn', 1),
('Eric', 1),
('Schmidt', 1),
('Google', 1),
('Chairman', 1),
('Broadly', 1),
('3', 1),
('Types', 1),
('Algorithmsrn1', 1),
('Supervised', 2),
('LearningrnHow', 3),
('Algorithm', 5),
('Consist', 1),
('Target', 2),
('Outcome', 4),
('Variable', 3),
('Dependent', 1),
('Predicted', 1),
('Set', 3),
('Predictors', 1),
('Independent', 2),
('Variables', 3),
('Using', 3),
('Generate', 1),
('Function', 2),
('Map', 1),
('Inputs', 1),
('Desired', 2),
('Outputs', 1),
('Training', 2),
('Process', 1),
('Continues', 1),
('Model', 1),
('Achieves', 1),
('Level', 1),
('Accuracy', 1),
('Data', 2),
('Examples', 2),
('Regression', 5),
('Decision', 2),
('Tree', 1),
('Random', 1),
('Forest', 1),
('KNN', 1),
('Logistic', 2),
('Etcrnrn', 1),
('Rnrn2', 1),
('Unsupervised', 2),
('Predict', 1),
('Estimate', 2),
('Clustering', 1),
('Population', 1),
('Widely', 1),
('Segmenting', 1),
('Customers', 1),
('Specific', 2),
('Intervention', 1),
('Apriori', 1),
('Kmeansrnrn', 1),
('Rnrn3', 1),
('Reinforcement', 2),
('Trained', 1),
('Decisions', 2),
('Exposed', 1),
('Environment', 1),
('Trains', 1),
('Continually', 1),
('Trial', 1),
('Error', 1),
('Learns', 1),
('Past', 1),
('Experience', 1),
('Tries', 1),
('Capture', 1),
('Knowledge', 1),
('Accurate', 1),
('Business', 1),
('Example', 1),
('Markov', 1),
('Processrnrn', 1),
('Dont', 2),
('Confused', 1),
('Name', 1),
('Classification', 1),
('Discrete', 1),
('Values', 3),
('Binary', 1),
('01', 1),
('Yesno', 1),
('Truefalse', 1),
('Based', 2),
('Simple', 2),
('Words', 1),
('Predicts', 2),
('Probability', 3),
('Occurrence', 1),
('Event', 1),
('Fitting', 1),
('Logit', 2),
('Hence', 1),
('Output', 1),
('Lies', 1),
('0', 1),
('1', 1),
('ExpectedrnrnAgain', 1),
('Try', 1),
('Understand', 2),
('ExamplernrnLets', 1),
('Friend', 1),
('Puzzle', 1),
('Solve', 3),
('2', 1),
('Scenarios', 1),
('Imagine', 1),
('Wide', 1),
('Range', 1),
('Puzzles', 1),
('Quizzes', 1),
('Attempt', 1),
('Subjects', 1),
('Study', 1),
('Trignometry', 1),
('Tenth', 1),
('Grade', 2),
('70', 1),
('Hand', 1),
('Fifth', 1),
('History', 1),
('Question', 1),
('Getting', 1),
('Answer', 1),
('30', 1),
('Provides', 1),
('Hello', 1),
('Posting', 1),
('Editorials', 1),
('Codechef', 1),
('Post', 2),
('Posts', 3),
('Friends', 2),
('Youtube', 1),
('Videos', 1),
('Embedded', 1),
('Writing', 1),
('Url', 1),
('News', 1),
('Feedrn', 1),
('Sharmarnis', 1),
('RnCoolrnbut', 1),
('Lazy', 1),
('Anythingrn', 1),
('RnEnter', 1),
('Wallpaper', 1),
('Sharmamamammarn', 1),
('Talk', 1),
('Importantrn', 1),
('DevRant', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `signup_date` date NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `num_posts` int(11) NOT NULL,
  `num_likes` int(11) NOT NULL,
  `user_closed` varchar(3) NOT NULL,
  `friend_array` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `signup_date`, `profile_pic`, `num_posts`, `num_likes`, `user_closed`, `friend_array`) VALUES
(26, 'Ishank', 'Sharma', 'ishank_sharma', 'Ishank@gmail.com', 'fc5e038d38a57032085441e7fe7010b0', '2019-08-06', 'assets/images/profile_pics/5d49168c679caimage_2.jpg', 0, 0, 'no', ','),
(29, 'Sagar', 'Gaur', 'sagar_gaur', 'Sagar@gmail.com', 'fc5e038d38a57032085441e7fe7010b0', '2019-08-06', 'assets/images/profile_pics/default/image_5.jpg', 0, 0, 'no', ','),
(31, 'Aman', 'Bhatnagar', 'aman_bhatnagar', 'Aman@gmail.com', 'fc5e038d38a57032085441e7fe7010b0', '2019-08-06', 'assets/images/profile_pics/default/image_5.jpg', 0, 0, 'no', ','),
(32, 'Aa', 'Aa', 'aa_aa', 'A@a.a', '594f803b380a41396ed63dca39503542', '2019-08-06', 'assets/images/profile_pics/default/image_10.jpg', 1, 0, 'no', ',');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
