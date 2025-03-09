const ImageProduct = ({ product }) => {
  return (
    <div className="gambar-produk">
      <div className="gambar-utama">
        <img src={product.image} alt={product.title} className="image" />
      </div>
      <div className="flex gap-2 mt-2">
        <div className="mini-image"></div>
        <div className="mini-image"></div>
        <div className="mini-image"></div>
      </div>
    </div>
  );
};

export default ImageProduct;
