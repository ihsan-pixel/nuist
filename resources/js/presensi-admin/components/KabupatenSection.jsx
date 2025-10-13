import React from 'react';
import MadrasahCard from './MadrasahCard';

const KabupatenSection = ({ kabupatenData, selectedDate }) => {
    const kabupatenOrder = [
        'Kabupaten Gunungkidul',
        'Kabupaten Bantul',
        'Kabupaten Kulon Progo',
        'Kabupaten Sleman',
        'Kota Yogyakarta'
    ];

    return (
        <>
            {kabupatenOrder.map(kabupaten => {
                const filteredData = kabupatenData.filter(data =>
                    data.madrasah && data.madrasah.kabupaten === kabupaten
                );

                if (filteredData.length === 0) return null;

                return (
                    <div key={kabupaten}>
                        <div className="row mb-3">
                            <div className="col-12">
                                <h5 className="text-primary mb-3">
                                    <i className="bx bx-map-pin me-2"></i>{kabupaten}
                                </h5>
                            </div>
                        </div>

                        <div className="row mb-4">
                            {filteredData.map((data, index) => (
                                <MadrasahCard
                                    key={`${kabupaten}-${index}`}
                                    madrasahData={data}
                                    selectedDate={selectedDate}
                                />
                            ))}
                        </div>
                    </div>
                );
            })}
        </>
    );
};

export default KabupatenSection;
