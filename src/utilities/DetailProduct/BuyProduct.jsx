import { ChatText, Heart, Share } from "phosphor-react";
import React from "react";

const BuyProduct = ({ product, quantity, setQuantity, selectedColor }) => {
  const colorMap = {
    Putih: "bg-gray-200",
    Hitam: "bg-black",
    Biru: "bg-blue-600",
    Merah: "bg-red-600",
  };

  const translateColor = colorMap[selectedColor] || "gray-200";

  return (
    <div className="pembelian-produk">
      <p className="font-semibold">Atur jumlah dan catatan</p>
      <div className="w-full flex flex-col gap-3">
        <div className="flex items-center gap-2">
          <div className={`colors ${translateColor}`}></div>
          <p>{selectedColor}</p>
        </div>
        <div className="flex flex-col lg:items-center md:items-start sm:items-center items-center gap-2">
          <div className="flex items-center w-full justify-between">
            <div className="quantity">
              <button
                onClick={() => setQuantity(Math.max(1, quantity - 1))}
                className="p-1 rounded"
              >
                −
              </button>
              <input
                type="number"
                value={quantity}
                min="1"
                className=""
                readOnly
              />
              <button
                onClick={() => setQuantity(quantity + 1)}
                className="p-1 rounded"
              >
                +
              </button>
            </div>
            <p>
              Stock: <span>1000</span>
            </p>
          </div>
          <div className="flex items-center justify-between w-full">
            <p className="text-sm">Subtotal:</p>
            <p className="text-xl font-semibold">${product.price * quantity}</p>
          </div>
        </div>
        <div className="button">
          <button className="w-full py-2 text-white bg-primary hover:bg-primary-hover rounded-md">
            + Keranjang
          </button>
          <button className="w-full py-2 border-2 border-primary rounded-md text-primary">
            Beli Langsung
          </button>
        </div>
      </div>
      <div className="flex items-center justify-center lg:w-full lg:justify-around gap-2 lg:gap-0">
        <div className="flex items-center gap-2">
          <ChatText size={24} />
          <p className="text-sm font-medium">Chat</p>
        </div>
        <div className="flex items-center gap-2 after:content-['|'] before:content-['|'] after:ml-2 after:text-gray-400 before:ml-2 before:text-gray-400">
          <Heart size={24} />
          <p className="text-sm font-medium">Wishlist</p>
        </div>
        <div className="flex items-center gap-2">
          <Share size={24} />
          <p className="text-sm font-medium">Share</p>
        </div>
      </div>
    </div>
  );
};

export default BuyProduct;
