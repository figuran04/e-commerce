import { SquaresFour } from "phosphor-react";
import "../../styles/Kategori.css";

const Pilihan = () => {
  return (
    <div className="pilihan">
      <div className="flex flex-col lg:flex-row gap-2">
        <div className="w-full flex flex-col gap-3">
          <h3 className="font-semibold">Kategori Pilihan</h3>
          <div className="flex gap-4 justify-between lg:justify-normal">
            <div className="size-28 rounded-xl bg-gray-200"></div>
            <div className="size-28 rounded-xl bg-gray-200"></div>
            <div className="size-28 rounded-xl bg-gray-200"></div>
            <div className="size-28 rounded-xl bg-gray-200"></div>
          </div>
        </div>
        <div className="w-full flex flex-col gap-3">
          <h3 className="font-semibold">Top Up & Tagihan</h3>
          <div className="border rounded-xl border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)]">
            <div className="flex w-full justify-around border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] border-b">
              <p className="py-2 w-full text-center text-primary border-b border-primary">
                Pulsa
              </p>
              <p className="py-2 w-full text-center">Paket Data</p>
              <p className="py-2 w-full text-center">Flight</p>
              <p className="py-2 w-full text-center">Listrik PLN</p>
            </div>
            <div className="flex p-4 gap-2 items-end">
              <div>
                <p>Nomor Telepon</p>
                <input
                  type="text"
                  className="w-full border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] px-2 py-1 rounded-lg"
                  placeholder="Masukkan Nomor"
                />
              </div>
              <div>
                <p>Nomor Telepon</p>
                <input
                  type="text"
                  className="w-full border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] px-2 py-1 rounded-lg"
                />
              </div>
              <button className="px-6 py-1 border border-transparent rounded-lg font-semibold bg-gray-200 text-gray-500">
                Beli
              </button>
            </div>
          </div>
        </div>
      </div>
      <div className="flex gap-2 items-center overflow-hidden overflow-x-scroll ">
        <div className="flex gap-1 items-center border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] rounded-lg px-2 py-1">
          <SquaresFour size={32} />
          <p>Kategori</p>
        </div>
        <div className="flex gap-1 items-center border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] rounded-lg px-2 py-1">
          <SquaresFour size={32} />
          <p>Kategori</p>
        </div>
        <div className="flex gap-1 items-center border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] rounded-lg px-2 py-1">
          <SquaresFour size={32} />
          <p>Kategori</p>
        </div>
        <div className="flex gap-1 items-center border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] rounded-lg px-2 py-1">
          <SquaresFour size={32} />
          <p>Kategori</p>
        </div>
        <div className="flex gap-1 items-center border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] rounded-lg px-2 py-1">
          <SquaresFour size={32} />
          <p>Kategori</p>
        </div>
        <div className="flex gap-1 items-center border border-[rgba(0,0,0,0.2)] dark:border-[rgba(255,255,255,0.3)] rounded-lg px-2 py-1">
          <SquaresFour size={32} />
          <p>Kategori</p>
        </div>
      </div>
    </div>
  );
};

export default Pilihan;
