const Carousel = () => {
  return (
    <div className="overflow-hidden rounded-lg shadow">
      <div className="carousel">
        <div className="carousel-item">
          <img
            src="https://images.tokopedia.net/img/cache/1208/NsjrJu/2025/2/28/05bef05b-6fb1-4027-a8ec-4bd4f2f6c984.jpg.webp?ect=3g"
            alt="Slide 1"
            className="object-cover w-full rounded-md sm:h-36 md:h-48 lg:h-64 xl:h-80"
          />
        </div>

        <div className="carousel-item">
          <img
            src="https://images.tokopedia.net/img/cache/1208/NsjrJu/2025/3/6/3f1b1477-7ea9-4bbd-a1df-1932bb7f2e61.jpg.webp?ect=3g"
            alt="Slide 2"
            className="object-cover w-full rounded-md sm:h-36 md:h-48 lg:h-64 xl:h-80"
          />
        </div>

        <div className="carousel-item">
          <img
            src="https://images.tokopedia.net/img/cache/1208/NsjrJu/2025/3/3/bec444fc-a066-487c-bdf1-58c7daf9d454.jpg.webp?ect=3g"
            alt="Slide 3"
            className="object-cover w-full rounded-md sm:h-36 md:h-48 lg:h-64 xl:h-80"
          />
        </div>
      </div>
    </div>
  );
};

export default Carousel;
