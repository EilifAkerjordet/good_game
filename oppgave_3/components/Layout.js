const Layout = ({ children }) => {
  return (
    <>
      <div className='wrapper'>
        {children}
      </div>

      <style global jsx>
        {`
          * {
            margin: 0;
            padding: 0;
            border: 0;
            box-sizing: border-box;
          }
          html, body {
            height: 100%;
            background-color: #212121;
          }

        `}
      </style>
      <style jsx>
        {
          `
            .wrapper {
              width: 50%;
              height: 500px;
              position: absolute;
              left: 50%;
              top: 50%;
              -webkit-transform: translate(-50%, -50%);
              transform: translate(-50%, -50%);
              padding: 10px;
              border-radius: 5px;
              background-color: #424242;
              box-shadow: 0px 0px 17px 4px rgba(0,0,0,0.75);
              -webkit-box-shadow: 0px 0px 17px 4px rgba(0,0,0,0.75);
              -moz-box-shadow: 0px 0px 17px 4px rgba(0,0,0,0.75);
            }
          `
        }
      </style>
    </>
  )
}

export default Layout
