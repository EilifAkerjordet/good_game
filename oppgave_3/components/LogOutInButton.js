const LogOutInButton = ({ login = false, ...props }) => {
  const href = login ? "/auth/steam" : "/auth/logout";
  const image = login ? "/static/steam.png" : "/static/logout.webp";
  const buttonText = login ? "Log in using Steam!" : "Log out";
  return (
    <a href={href} rel="noopener noreferrer" className="link">
      <div className="wrapper" {...props}>
        <img className="icon" src={image} alt="image" />
        <h3 className="text">{buttonText}</h3>
      </div>

      <style jsx>
        {`
          .link {
            color: inherit;
            text-decoration: none;
          }
          .wrapper {
            border-radius: 5px;
            background-color: #ad1457;
            padding: 10px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease;
          }
          .wrapper:hover {
            pointer: cursor;
            background-color: #d81b60;
          }
          .icon {
            width: 50px;
          }
          .text {
            padding-left: 10px;
            color: white;
          }
        `}
      </style>
    </a>
  );
};

export default LogOutInButton;
