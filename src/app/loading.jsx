const Loading = () => {
  return (
    <div className="flex justify-center items-center min-h-screen">
      <div className="flex justify-center items-center loading mb-64"></div>
      <div className="glow-wrapper">
        <div className="glow green"></div>
        <div className="glow pink"></div>
      </div>
    </div>
  );
};

export default Loading;
