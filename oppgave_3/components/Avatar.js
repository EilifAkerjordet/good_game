const Avatar = ({ size, ...props }) => {
  return (
    <>
      <img
        {...props}
        className='avatar'
      />
      <style jsx>
        {`
          .avatar {
            width: ${size || 35};
            height: ${size || 35};
            border-radius: 50%;
            vertical-align: bottom;
            object-fit: cover;
          }
        `}
      </style>
    </>
  )
}

export default Avatar
