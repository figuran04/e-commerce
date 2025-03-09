import Link from "next/link";
import { Star } from "phosphor-react";

const Card = ({ id, product }) => {
  return (
    <Link href={`product/${id}`} className="card">
      <div className="image">
        <img src={product.image} alt={product.title} />
      </div>
      <div className="p-2">
        <h4 className="title">{product.title}</h4>
        <p className="text-xl font-semibold">${product.price}</p>
        <p className="line-clamp-1">{product.category}</p>
        <div className="flex items-center gap-1">
          <Star className="text-yellow-500" size={16} weight="fill" />
          {product.rating.rate} | {product.rating.count} ulasan
        </div>
      </div>
    </Link>
  );
};

export default Card;
