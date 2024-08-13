import React,{ useState } from 'react'
import Preloader from '../Components/Preloader'
import {usePage,useForm,Link } from '@inertiajs/react'
import { baseUrl } from '../Components/Baseurl'

function MyProfile() {
    const {user,city,state,country,userSession,flash} = usePage().props;
       
    const [stateList,setStateList]= useState(user.country != null ? state.filter(state => state.country == user.country) : []);
    const [cityList,setCityList]= useState(user.state != null ? city.filter(city => city.state == user.state) : []);
       
    const handleCountryChange = (val) => {
        setStateList(state.filter(state => state.country == val) );
    };
    
    const handleStateChange = (val) => {
        setCityList(city.filter(city => city.state == val) );
    };

    const {data,setData,post,processing,errors } = useForm({
        name: user.name || '',
        img: '',
        phone: user.phone || '',
        country: user.country != null ? user.country : '',
        state: user.state != null ? user.state : '',
        city: user.city != null ? user.city : '',
        address: user.address || '',
        code: user.pin_code || '',
    })
   // console.log(data);

    function handleChange(e) {
        const key = e.target.name;
        const value = e.target.value;
        setData((prevdata) => ({ ...prevdata, [key]: value }));
        if(key == 'country'){
            handleCountryChange(value);
        }else if(key == 'state'){
            handleStateChange(value);
        }
    }

    const handleFileChange = e => {
        setData("img", e.target.files[0]);
    }
  
    function handleSubmit(e) {
        e.preventDefault();
        post(baseUrl+'/my-profile',{
            preserveScroll: true,
        });
    }

    // function handleImageChange(e) {
    //     if (e.target.files && e.target.files[0]) {
    //         const reader = new FileReader();
    //         reader.onload = function (e) {
    //         setImagePreview(e.target.result);
    //         };
    //         reader.readAsDataURL(e.target.files[0]);
    //     }
    // }

    return (
    <div id="site-content">
        <div id="banner" className="d-flex flex-row justify-content-center">
            <div className="align-self-center">
                <h2>My Profile</h2>
                <nav aria-label="breadcrumb">
                    <ol className="breadcrumb justify-content-center p-0">
                        <li className="breadcrumb-item"><Link href={baseUrl}>Home</Link></li>
                        <li className="breadcrumb-item active">My Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div className="container-xl container-fluid">
            <form className="row" onSubmit={handleSubmit} method="post" style={{ width: '100%' }}>
                {processing && <Preloader />}
                <div className="col-md-3">
                    <div className="content-box">
                        <img id="image" className="mb-2 w-100" src={user.user_img ? baseUrl+'/public/users/' + user.user_img : baseUrl+'/public/users/default.png'} alt={data.name}/>
                        <div>
                            <input type="file" className="form-control" name="img" onChange={handleFileChange} width="100%" />
                            {errors.user_img && <div className="alert alert-danger mt-2" role="alert">{errors.user_img}</div>}
                        </div>
                    </div>
                </div>
                <div className="col-md-9">
                    <div className="content-box">
                        <div className="form-group row mb-3">
                            <label className="col-lg-3 col-sm-5 col-form-label">Full Name : </label>
                            <div className="col-lg-5 col-sm-7">
                                <input type="text" className="form-control" name="name" value={data.name} onChange={handleChange} />
                                {errors.name && <div className="alert alert-danger mt-2" role="alert">{errors.name}</div>}
                            </div>
                        </div>
                        <div className="form-group row mb-3">
                            <label htmlFor="staticphone" className="col-lg-3 col-sm-5 col-form-label">Phone No : </label>
                            <div className="col-lg-5 col-sm-7">
                                <input type="number" className="form-control" name="phone" value={data.phone} onChange={handleChange} />
                                {errors.phone && <div className="alert alert-danger mt-2" role="alert">{errors.phone}</div>}
                            </div>
                        </div>
                        <div className="form-group row mb-3">
                            <label htmlFor="staticphone" className="col-lg-3 col-sm-5 col-form-label">Country : </label>
                            <div className="col-lg-5 col-sm-7">
                                <select className="form-control select-country" name="country" value={data.country} onChange={handleChange}>
                                    <option value="">Select Country</option>
                                    {country.map((country) => (
                                    <option key={country.id} value={country.id}>
                                        {country.country_name}
                                    </option>
                                    ))}
                                </select>
                                {errors.country && <div className="alert alert-danger mt-2" role="alert">{errors.country}</div>}
                            </div>
                        </div>
                        <div className="form-group row mb-3">
                            <label htmlFor="staticphone select-state" className="col-lg-3 col-sm-5 col-form-label">Province/City :</label>
                            <div className="col-lg-5 col-sm-7">
                                <select className="form-control" name="state" id="state" value={data.state} onChange={handleChange}>
                                    <option value="">First Select Country</option>
                                    {stateList.map((state) => (
                                    <option key={state.id} value={state.id}>
                                        {state.state_name}
                                    </option>
                                    ))}
                                </select>
                                {errors.state && <div className="alert alert-danger mt-2" role="alert">{errors.state}</div>}
                            </div>
                        </div>
                        <div className="form-group row mb-3">
                            <label htmlFor="staticphone" className="col-lg-3 col-sm-5 col-form-label">District :</label>
                            <div className="col-lg-5 col-sm-7">
                                <select className="form-control" name="city" id="city" value={data.city} onChange={handleChange}>
                                    <option value="">First Select State</option>
                                    {cityList.map((city) => (
                                    <option key={city.id} value={city.id}>
                                        {city.city_name}
                                    </option>
                                    ))}
                                </select>
                                {errors.city && <div className="alert alert-danger mt-2" role="alert">{errors.city}</div>}
                            </div>
                        </div>  
                        <div className="form-group row mb-3">
                            <label htmlFor="staticphone" className="col-lg-3 col-sm-5 col-form-label">Address :</label>
                            <div className="col-lg-5 col-sm-7">
                                <input type="text" className="form-control" name="address" value={data.address} onChange={handleChange}/>
                                {errors.address && <div className="alert alert-danger mt-2" role="alert">{errors.address}</div>}
                            </div>
                        </div>
                        <div className="form-group row mb-3">
                            <label htmlFor="staticphone" className="col-lg-3 col-sm-5 col-form-label">Pin Code :</label>
                            <div className="col-lg-5 col-sm-7">
                                <input type="number" className="form-control" name="code" value={data.code} onChange={handleChange}/>
                                {errors.pincode && <div className="alert alert-danger mt-2" role="alert">{errors.pincode}</div>}
                            </div>
                        </div>
                        <button type="submit" disabled={processing} className="btn btn-primary mb-2">
                            UPDATE
                        </button>   
                    </div>  
                </div> 
                {/* Display error message if exists */}
                {flash.error && (<div className="alert alert-danger mt-2" role="alert"> {flash.error} </div> )}
                {/* Display success message if exists */}
                {flash.success && (<div className="alert alert-success mt-2" role="alert"> {flash.success} </div> )}
            </form>
        </div>
    </div>
    );
}
export default MyProfile;
